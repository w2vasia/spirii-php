<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PayoutController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users/{id}/summary', [UserController::class, 'summary']);
Route::post('/users/{id}/payout', [UserController::class, 'payout']);
Route::get('/payouts/requests', [PayoutController::class, 'index']);
Route::patch('/payouts/{id}/approve', [PayoutController::class, 'approve']);
