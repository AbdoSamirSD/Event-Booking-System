<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_organizer_can_create_event(): void
    {
        $organizer = User::factory()->create([
            'role' => 'organizer',
        ]);

        $response = $this->actingAs($organizer)->postJson('/api/events', [
            'title' => 'Sample Event',
            'description' => 'This is a sample event description.',
            'date' => '2024-12-31',
            'location' => 'Sample Location',
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'message', 'event' => ['id', 'title', 'description', 'date', 'location']
        ]);
    }
}
