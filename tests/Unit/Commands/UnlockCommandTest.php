<?php

namespace Tests\Unit\Commands;

use Dungeon\Commands\UnlockCommand;
use Dungeon\Portal;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UnlockCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function a_portal_locked_with_a_code_can_be_unlocked_with_a_code()
    {
        $start_room = $this->makeRoom();
        $other_room = $this->makeRoom([
            'description' => 'This is the second room',
        ]);

        $portal = factory(Portal::class)->create();
        $portal->lockWithCode('1234');
        $portal->save();

        $start_room->setNorthExit($other_room, [
            'portal_id' => $portal->id,
        ]);

        $user = $this->makeUser([], 100, $start_room);

        $command = new UnlockCommand('unlock north door with code 1234', $user);
        $command->execute();

        $portal->refresh();

        $this->assertFalse($portal->isLocked());
        $this->assertTrue($command->success);
        $this->assertStringContainsString('You unlock the door.', $command->getMessage());
    }

    /** @test */
    public function if_direction_is_not_a_cardinal_direction_command_fails()
    {
        $user = $this->makeUser();
        $command = new UnlockCommand('unlock blah door with code 1234', $user);
        $command->execute();

        $this->assertFalse($command->success);
    }

    /** @test */
    public function if_access_type_is_invalid_command_fails()
    {
        $user = $this->makeUser();
        $command = new UnlockCommand('unlock north door with blah 1234', $user);
        $command->execute();

        $this->assertFalse($command->success);
    }
}
