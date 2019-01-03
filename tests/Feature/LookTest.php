<?php

namespace Tests\Feature;

use App\Room;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LookTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function look_command_returns_description()
    {
        $room = Room::create([
            'description' => 'This is a room.',
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secretmagicword',
        ]);
        
        $user->moveToRoom($room);

        $response = $this->actingAs($user)
            ->post('/cmd', [
                'input' => 'look'
            ]);

        $response->assertStatus(200);

        $response->assertJson([
             'message' => 'This is a room.',
             'response' => true,
        ]);
    }
}
