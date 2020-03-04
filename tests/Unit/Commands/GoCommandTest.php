<?php

namespace Tests\Unit\Commands;

use Dungeon\Commands\GoCommand;
use Dungeon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GoCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function if_no_direction_is_given_command_fails()
    {
        $user = $this->makeUser();

        $command = new GoCommand('go', $user);
        $command->execute();

        $this->assertFalse($command->success);
        $this->assertEquals('Go where?', $command->getMessage());
    }

    /** @test */
    public function if_unknown_direction_is_given_give_an_error()
    {
        $user = $this->makeUser();

        $command = new GoCommand('go fake_direction_that_doesnt_exists', $user);
        $command->execute();

        $this->assertFalse($command->success);
        $this->assertEquals('I don\'t know which way that is.', $command->getMessage());
    }

    /** @test */
    public function if_no_exit_in_chosen_direction_give_error()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);

        $command = new GoCommand('go east', $user);
        $command->execute();

        $this->assertFalse($command->success);
        $this->assertEquals('I can\'t go that way.', $command->getMessage());
    }

    /** @test */
    public function if_we_can_go_where_we_want_to_go_then_lets_go_there()
    {
        $north_room = $this->makeRoom([
                'description' => 'This is the north room.',
        ]);
        $south_room = $this->makeRoom([
                'description' => 'This is the south room.',
        ]);

        $north_room->setSouthExit($south_room);
        $user = $this->makeUser([], 100, $north_room);

        $this->assertEquals($north_room->id, $user->getRoom()->id);

        $command = new GoCommand('go south', $user);
        $command->execute();

        $this->assertTrue($command->success);
        $this->assertStringContainsString('You go', $command->getMessage());

        $user = User::first();

        $this->assertEquals($south_room->id, $user->getRoom()->id);
    }
}
