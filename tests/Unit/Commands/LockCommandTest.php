<?php

namespace Tests\Unit\Commands;

use Dungeon\Commands\LockCommand;
use Dungeon\Portal;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LockCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function a_portal_can_be_locked_with_a_code()
    {
        $start_room = $this->makeRoom();
        $other_room = $this->makeRoom();

        $portal = factory(Portal::class)->create([
            'locked' => false,
            'code' => null,
        ]);

        $start_room->setNorthExit($other_room, [
            'portal_id' => $portal->id,
        ]);

        $user = $this->makeUser([], 100, $start_room);

        $command = new LockCommand('lock north door with code 1234', $user);
        $command->execute();

        $portal->refresh();

        $this->assertTrue($command->success);
        $this->assertTrue($portal->isLocked());
        $this->assertStringContainsString('You lock the door.', $command->getMessage());
    }
}
