<?php

namespace Dungeon\Tests\Unit\Commands;

use Dungeon\Commands\RespawnCommand;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RespawnCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function respawn_command_fails_if_user_is_alive()
    {
        $user = $this->makeUser();

        $command = new RespawnCommand('respawn', $user);
        $command->execute();

        $this->assertEquals('Player isn\'t dead.', $command->getMessage());
        $this->assertFalse($command->success);
    }

    /** @test */
    public function respawn_command_gives_the_player_a_new_body_returns_them_to_the_start_room_and_makes_them_alive()
    {
        $room_1 = $this->makeRoom([
            'is_spawn_room' => true,
        ]);
        $room_2 = $this->makeRoom();
        $user = $this->makeUser([], 100, $room_2);
        $body_1 = $user->getBody();

        $user->hurt(999999); // That should do it.

        $command = new RespawnCommand('respawn', $user);
        $command->execute();

        $user->refresh();

        $this->assertEquals('You wake up.', $command->getMessage());
        $this->assertTrue($command->success);
        $this->assertNotNull($user->getBody());
        $this->assertNotEquals($body_1->id, $user->getBody()->id);
        $this->assertEquals($user->getRoom()->id, $room_1->id);
        $this->assertTrue($user->isAlive());
    }
}
