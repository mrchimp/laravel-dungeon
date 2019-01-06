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

        $this->north_room = Room::create([
            'description' => 'This is the north room.',
        ]);

        $this->south_room = Room::create([
            'description' => 'This is the south room.',
        ]);
    }

    /** @test */
    public function returns_null_if_user_not_logged_in()
    {
        $command = new LookCommand();

        $response = $command->run('look');

        $this->assertNull($response);
    }

    /** @test */
    public function gets_a_response_if_not_in_a_room()
    {
        $command = new LookCommand($this->user);

        $response = $command->run('look');

        $this->assertEquals(
            'You float in an endless void.',
            $response
        );
    }

    /** @test */
    public function gets_current_room_description_if_logged_in()
    {
        $this->user->moveToRoom($this->north_room);

        $command = new LookCommand($this->user);

        $response = $command->run('look');

        $this->assertEquals(
            'This is the north room.',
            $response
        );
    }

    /** @test */
    public function gets_exits()
    {
        $this->user->moveToRoom($this->north_room);
        $this->north_room->setSouthExit($this->south_room, [
            'description' => 'A wooden door.',
        ]);
        
        $command = new LookCommand($this->user);

        $response = $command->run('look');

        $this->assertStringContainsString('Exits:', $response);
        $this->assertStringContainsString('This is the north room.', $response);
        $this->assertStringContainsString('A wooden door.', $response);
    }
}