<?php

namespace Tests\Unit\Dungeon\Commands;

use App\Dungeon\Commands\GoCommand;
use App\Room;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GoCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    protected $user;

    protected $north_room;

    protected $south_room;

    protected $command;

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

        $this->user->moveTo($this->north_room);
        $this->north_room->setSouthExit($this->south_room);
        $this->command = new GoCommand($this->user);
    }

    /** @test */
    public function if_no_direction_is_given_give_error()
    {
        $response = $this->command->run('go');

        $this->assertEquals('Go where?', $response);
    }

    /** @test */
    public function if_unknown_direction_is_given_give_an_error()
    {
        $response = $this->command->run('go fake_direction_that_doesnt_exists');

        $this->assertEquals('I don\'t know which way that is.', $response);
    }

    /** @test */
    public function if_no_exit_in_chosen_direction_give_error()
    {
        $response = $this->command->run('go east');

        $this->assertEquals('I can\'t go that way.', $response);
    }

    /** @test */
    public function if_we_can_go_where_we_want_to_go_then_lets_go_there()
    {
        $this->assertEquals($this->north_room->id, $this->user->room_id);

        $response = $this->command->run('go south');

        $this->assertStringContainsString('You go', $response);
        $this->assertStringContainsString('This is the south room.', $response);

        $user = User::first();
        $this->assertEquals($this->south_room->id, $user->room_id);
    }
}