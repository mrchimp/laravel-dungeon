<?php

namespace Tests\Unit\Commands;

use Dungeon\Commands\InspectCommand;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class InspectCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function you_cant_inspect_things_that_you_cant_find()
    {
        $user = $this->makeUser();

        $command = new InspectCommand('inspect atlantis', $user);
        $command->execute();

        $this->assertStringContainsString('Could not find atlantis.', $command->getMessage());
    }

    /** @test */
    public function you_can_inspect_things_in_your_inventory()
    {
        $user = $this->makeUser();

        $potato = $this->makePotato()->giveToUser($user);
        $potato->save();

        $command = new InspectCommand('inspect potato', $user);
        $command->execute();

        $this->assertStringContainsString('A potato.', $command->getMessage());
    }

    /** @test */
    public function you_can_inspect_things_in_the_room()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);
        $potato = $this->makePotato()->moveToRoom($room);
        $potato->save();

        $command = new InspectCommand('inspect potato', $user);
        $command->execute();

        $this->assertStringContainsString('A potato.', $command->getMessage());
    }

    /** @test */
    public function you_can_inspect_things_in_containers()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);
        $box = $this->makeBox();
        $box->moveToRoom($room)->save();

        $potato = $this->makePotato()->moveToContainer($box);
        $potato->save();

        $command = new InspectCommand('inspect potato', $user);
        $command->execute();

        $this->assertStringContainsString('A potato.', $command->getMessage());
    }

    /** @test */
    public function you_can_see_the_contents_of_a_container_when_inspecting_it()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);
        $box = $this->makeBox()->moveToRoom($room);
        $box->save();

        $potato = $this->makePotato()->moveToContainer($box);
        $potato->save();

        $command = new InspectCommand('inspect box', $user);
        $command->execute();

        $this->assertStringContainsString('Potato', $command->getMessage());
    }
}
