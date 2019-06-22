<?php

namespace Tests\Feature;

use Dungeon\Entities\People\Body;
use Dungeon\Room;
use Dungeon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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

        $this->room = factory(Room::class)->create([
            'description' => 'This is a room.',
        ]);

        $this->user = factory(User::class)->create([
            'name' => 'Test User',
        ]);

        $this->body = factory(Body::class)->create();
        $this->body->giveToUser($this->user)->save();

        $this->user->moveTo($this->room)->save();
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
