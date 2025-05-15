<?php

namespace Tests\Feature;

use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTransactionStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_updates_transaction_status_via_api()
    {
        $transaction = Transaction::factory()->create([
            'status' => Transaction::STATUS_EMPTY,
        ]);

        $response = $this->patchJson("/api/payouts/{$transaction->id}/approve");

        $response->assertStatus(201);
        $response->assertJsonFragment(['status' => 'approved']);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'approved',
        ]);
    }
}
