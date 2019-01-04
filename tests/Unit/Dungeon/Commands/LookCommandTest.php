<?php

namespace Tests\Unit\Dungeon\Commands;

use App\Room;
use App\User;
use Tests\TestCase;
use App\Dungeon\Commands\LookCommand;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LookCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    protected $user;

    public function setup()
    {
        parent::setup();

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('fakepassword'),
        ]);
    }
    /** @test */
    public function returns_null_if_user_not_logged_in()
    {
        $command = new LookCommand();

        $response = $command->run('look');

        $this->assertNull($response['description']);
    }

    /** @test */
    public function gets_a_response_if_not_in_a_room()
    {
        $command = new LookCommand($this->user);

        $response = $command->run('look');

        $this->assertEquals(
            'You float in an endless void.',
            $response['description']
        );
    }

    /** @test */
    public function gets_current_room_description_if_logged_in()
    {
        $room = Room::create([
            'description' => 'This is a room.',
        ]);

        $this->user->moveToRoom($room);

        $command = new LookCommand($this->user);

        $response = $command->run('look');

        $this->assertEquals(
            'This is a room.',
            $response['description']
        );
    }
}