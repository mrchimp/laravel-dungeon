<?php

namespace Tests\Feature;

use App\Room;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LookTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    protected $room;

    protected $user;

    protected $banana;

    public function setup()
    {
        parent::setup();

        $this->room = Room::create([
            'description' => 'This is a room.',
        ]);

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secretmagicword',
        ]);

        $this->user->moveTo($this->room);
        $this->user->save();
    }

    /** @test */
    public function look_command_returns_room_description()
    {
        $response = $this->actingAs($this->user)
            ->post('/cmd', [
                'input' => 'look'
            ]);

        $response->assertStatus(200);

        $response->assertSee('This is a room.');
    }
}
