<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function summary(int $id): JsonResponse
    {
        $user = User::with('transactions')->find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $cachedSummary = Cache::remember("user_summary_{$id}", now()->addMinutes(2), function () use ($user) {
            $earnedAmount = $user->transactions()->ofType(Transaction::TYPE_EARNED)->sum('amount');
            $spentAmount = $user->transactions()->ofType(Transaction::TYPE_SPENT)->sum('amount');
            $payoutRequested = $user->transactions()->byStatus(Transaction::STATUS_REQUESTED)->sum('amount');

            return [
                'earned' => $earnedAmount,
                'spent' => $spentAmount,
                'payout_requested' => $payoutRequested,
            ];
        });

        $summary = [
            'userId' => $user->id,
            'earned' => $cachedSummary['earned'],
            'spent' => $cachedSummary['spent'],
            'payout_requested' => $cachedSummary['payout_requested'],
            'payout_approved' => $user->transactions()->byStatus(Transaction::STATUS_APPROVED)->sum('amount'),
            'payout_paid' => $user->transactions()->byStatus(Transaction::STATUS_PAID)->sum('amount'),
        ];

        // we calculate this value on the fly
        $summary['balance'] = $summary['earned'] - $summary['spent'] - $summary['payout_approved'];

        return response()->json(['data' => $summary]);
    }
}
