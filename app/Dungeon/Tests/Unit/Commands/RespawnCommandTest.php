<?php

namespace Dungeon\Tests\Unit\Commands;

use Dungeon\Actions\Entities\Hurt;
use Dungeon\Commands\RespawnCommand;
use Dungeon\DamageTypes\MeleeDamage;
use Dungeon\Entities\People\Body;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RespawnCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function respawn_command_fails_if_user_is_alive()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);

        $command = new RespawnCommand('respawn', $user);
        $command->execute();

        $this->assertEquals('You\'re already alive!', $command->getMessage());
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

        Hurt::do($user->body, 99999, MeleeDamage::class); // That should do it.

        $user->refresh();

        $this->assertTrue($user->isDead());

        $command = new RespawnCommand('respawn', $user);
        $command->execute();

        $user->refresh();

        $this->assertEquals('You wake up.', $command->getMessage());
        $this->assertTrue($command->success);
        $this->assertNotNull($user->getBody());
        $this->assertInstanceOf(Body::class, $user->getBody());
        $this->assertNotEquals($body_1->id, $user->getBody()->id);
        $this->assertEquals($user->getRoom()->id, $room_1->id);
        $this->assertTrue($user->isAlive());
    }
}
