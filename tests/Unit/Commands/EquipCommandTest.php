<?php

namespace Tests\Unit\Dungeon\Commands;

use Dungeon\Commands\EquipCommand;
use Dungeon\Entity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EquipCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function apparel_can_be_equipped()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);
        $hat = $this->makeHat([
            'name' => 'Hat',
        ])->giveToUser($user);
        $hat->save();

        $command = new EquipCommand('equip hat', $user);
        $command->execute();

        $hat = Entity::find($hat->id);

        $this->assertEquals(1, $hat->equiped);
        $this->assertTrue($command->success);
    }

    /** @test */
    public function you_cant_equip_a_potato()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);
        $potato = $this->makePotato()->giveToUser($user);
        $potato->save();

        $command = new EquipCommand('equip potato', $user);
        $command->execute();

        $potato = Entity::find($potato->id);

        $this->assertEquals(0, $potato->equiped);
        $this->assertFalse($command->success);
    }

    /** @test */
    public function you_cant_equip_something_you_dont_have()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);
        $hat = $this->makeHat()->moveToRoom($room);
        $hat->save();

        $command = new EquipCommand('equip hat', $user);
        $command->execute();

        $hat = Entity::find($hat->id);

        $this->assertEquals(0, $hat->equiped);
        $this->assertFalse($command->success);
    }

    /** @test */
    public function you_cant_equip_something_if_you_dont_know_what_it_is()
    {
        $user = $this->makeUser();

        $command = new EquipCommand('equip the ineffable', $user);
        $command->execute();

        $this->assertFalse($command->success);
    }
}
