<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PayoutController extends Controller
{
    public function index(): JsonResponse
    {
        $results = DB::table('transactions')
        ->select('user_id as userId', DB::raw('SUM(amount) as total_requested'))
            ->groupBy('user_id')
            ->get();

        return response()->json($results);
    }

    public function approve(string $id): JsonResponse
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found.'], 404);
        }

        $transaction->approve();
        $transaction->save();

        Cache::forget("user_{$transaction->user_id}_summary");

        return response()->json([
            'message' => 'Transaction approved successfully',
            'data' => $transaction,
        ], 201);
    }
}
