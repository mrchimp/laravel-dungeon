<?php

namespace Tests\Unit\Commands;

use Dungeon\Commands\LockCommand;
use Dungeon\Entities\Locks\Key;
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

        $portal = $this->makePortal([
            'locked' => false,
            'code' => 1234,
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

    /** @test */
    public function a_portal_cannot_be_locked_with_a_code_if_it_doesnt_have_a_code_set()
    {
        $start_room = $this->makeRoom();
        $other_room = $this->makeRoom();

        $portal = $this->makePortal([
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

        $this->assertFalse($command->success);
        $this->assertFalse($portal->isLocked());
        $this->assertStringContainsString('You can\'t lock that door with a code.', $command->getMessage());
    }

    /** @test */
    public function a_portal_can_be_locked_with_a_key_that_fits()
    {
        $start_room = $this->makeRoom();
        $other_room = $this->makeRoom();
        $portal = $this->makePortal([
            'locked' => false,
            'code' => null,
        ]);
        $key = factory(Key::class)->create();
        $portal->keys()->attach($key->id);
        $start_room->setNorthExit($other_room, [
            'portal_id' => $portal->id,
        ]);
        $user = $this->makeUser([], 100, $start_room);
        $key->giveToUser($user)->save();

        $command = new LockCommand('lock north door with key', $user);
        $command->execute();

        $portal->refresh();

        $this->assertEquals('You lock the door.', $command->getMessage());
        $this->assertTrue($command->success);
        $this->assertTrue($portal->isLocked());
    }

    /** @test */
    public function a_portal_cannot_be_unlocked_without_an_appropriate_key()
    {
        $start_room = $this->makeRoom();
        $other_room = $this->makeRoom();
        $portal = $this->makePortal([
            'locked' => false,
            'code' => null,
        ]);
        $key = factory(Key::class)->create();
        $portal->keys()->attach($key->id);
        $start_room->setNorthExit($other_room, [
            'portal_id' => $portal->id,
        ]);
        $user = $this->makeUser([], 100, $start_room);

        $command = new LockCommand('lock north door with key', $user);
        $command->execute();

        $portal->refresh();

        $this->assertEquals('Nothing fits!', $command->getMessage());
        $this->assertFalse($command->success);
        $this->assertFalse($portal->isLocked());
    }

    /** @test */
    public function code_can_be_bypassed_if_a_key_fits()
    {
        $start_room = $this->makeRoom();
        $other_room = $this->makeRoom();
        $portal = $this->makePortal([
            'locked' => false,
            'code' => 1234,
        ]);
        $key = factory(Key::class)->create();
        $portal->keys()->attach($key->id);
        $start_room->setNorthExit($other_room, [
            'portal_id' => $portal->id,
        ]);
        $user = $this->makeUser([], 100, $start_room);
        $key->giveToUser($user)->save();

        $command = new LockCommand('lock north door with key', $user);
        $command->execute();

        $portal->refresh();

        $this->assertEquals('You lock the door.', $command->getMessage());
        $this->assertTrue($command->success);
        $this->assertTrue($portal->isLocked());
    }
}
