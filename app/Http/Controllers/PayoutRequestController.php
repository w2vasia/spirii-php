<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PayoutRequestController extends Controller
{
    public function index(): JsonResponse
    {
        $results = DB::table('transactions')
        ->select('user_id as userId', DB::raw('SUM(amount) as total_requested'))
            ->groupBy('user_id')
            ->get();

        return response()->json($results);
    }
}
