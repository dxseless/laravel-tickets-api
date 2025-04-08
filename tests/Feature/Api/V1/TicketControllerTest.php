<?php

namespace Tests\Feature\Api\V1;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_tickets()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        Ticket::factory(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/v1/tickets');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'title',
                            'status',
                            'createdAt'
                        ]
                    ]
                ]
            ]);
    }
}