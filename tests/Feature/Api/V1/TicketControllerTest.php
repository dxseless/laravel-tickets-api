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
                            'createdAt',
                        ],
                    ],
                ],
            ]);
    }

    public function test_store_creates_ticket()
    {
        $user = User::factory()->create(['is_manager' => false]);
        Sanctum::actingAs($user, ['ticket:own:create']);

        $response = $this->postJson('/api/v1/tickets', [
            'data' => [
                'attributes' => [
                    'title' => 'New Ticket',
                    'description' => 'Test description',
                    'status' => 'Active',
                ],
                'relationships' => [
                    'user' => [
                        'data' => ['id' => $user->id],
                    ],
                ],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'attributes' => [
                        'title' => 'New Ticket',
                    ],
                ],
            ]);

        $this->assertDatabaseHas('tickets', [
            'title' => 'New Ticket',
            'user_id' => $user->id,
        ]);
    }

    public function test_store_requires_authentication()
    {
        $response = $this->postJson('/api/v1/tickets', [
            'data' => [
                'attributes' => [
                    'title' => 'New Ticket',
                ],
            ],
        ]);

        $response->assertStatus(401);
    }

    public function test_store_validates_input()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/tickets', [
            'data' => [
                'attributes' => [
                    'title' => '',
                    'status' => 'InvalidStatus',
                ],
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'data.attributes.title',
                'data.attributes.status',
            ]);
    }

    public function test_destroy_denies_foreign_ticket_deletion()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $ticket = Ticket::factory()->create(['user_id' => $user1->id]);

        Sanctum::actingAs($user2, ['ticket:own:delete']);

        $response = $this->deleteJson("/api/v1/tickets/{$ticket->id}");

        $response->assertStatus(403);
    }

    public function test_index_filters_by_status()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Ticket::factory()->create([
            'status' => 'Active',
            'user_id' => $user->id,
        ]);
        Ticket::factory()->create([
            'status' => 'Completed',
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/api/v1/tickets?filter[status]=Active');

        // Проверяем ответ
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'status' => 'Active',
            ]);
    }
}
