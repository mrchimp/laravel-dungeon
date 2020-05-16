<?php

namespace Tests\Unit\Commands;

use Dungeon\Commands\UnlockCommand;
use Dungeon\Entities\Locks\Code;
use Dungeon\Entities\Locks\Key;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UnlockCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function a_portal_can_be_unlocked_with_a_code()
    {
        $start_room = $this->makeRoom();
        $other_room = $this->makeRoom([
            'description' => 'This is the second room',
        ]);

        $portal = $this->makePortal([
            'locked' => true,
        ]);

        $code = $this->makeCode('1234');
        $portal->keys()->attach($code->id);

        $start_room->setNorthExit($other_room, [
            'portal_id' => $portal->id,
        ]);

        $user = $this->makeUser([], 100, $start_room);

        $code->giveToUser($user)->save();

        $command = new UnlockCommand('unlock north door', $user);
        $command->execute();

        $portal->refresh();

        $this->assertStringContainsString('You unlock the door.', $command->getMessage());
        $this->assertTrue($command->success);
        $this->assertFalse($portal->isLocked());
    }

    /** @test */
    public function if_direction_is_not_a_cardinal_direction_command_fails()
    {
        $user = $this->makeUser();
        $command = new UnlockCommand('unlock blah door', $user);
        $command->execute();

        $this->assertFalse($command->success);
    }

    /** @test */
    public function a_portal_can_be_unlocked_with_a_key()
    {
        $start_room = $this->makeRoom();
        $other_room = $this->makeRoom();
        $portal = $this->makePortal([
            'locked' => true,
        ]);
        $key = factory(Key::class)->create();
        $portal->keys()->attach($key->id);
        $start_room->setNorthExit($other_room, [
            'portal_id' => $portal->id,
        ]);
        $user = $this->makeUser([], 100, $start_room);
        $key->giveToUser($user)->save();

        $command = new UnlockCommand('unlock north door', $user);
        $command->execute();

        $portal->refresh();

        $this->assertFalse($portal->isLocked());
        $this->assertTrue($command->success);
        $this->assertStringContainsString('You unlock the door.', $command->getMessage());
    }

    /** @test */
    public function a_portal_cannot_be_unlocked_without_a_key()
    {
        $start_room = $this->makeRoom();
        $other_room = $this->makeRoom();

        $portal = $this->makePortal([
            'locked' => true,
        ]);

        $code = factory(Code::class)->create();
        $portal->keys()->attach($code->id);
        $start_room->setNorthExit($other_room, [
            'portal_id' => $portal->id,
        ]);
        $user = $this->makeUser([], 100, $start_room);
        $code->giveToUser($user)->save();

        $command = new UnlockCommand('unlock north door', $user);
        $command->execute();

        $portal->refresh();

        $this->assertEquals('You unlock the door.', $command->getMessage());
        $this->assertTrue($command->success);
        $this->assertFalse($portal->isLocked());
    }
}
