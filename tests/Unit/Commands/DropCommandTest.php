<?php

namespace Tests\Unit\Commands;

use Dungeon\Commands\DropCommand;
use Dungeon\Entity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DropCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function setup()
    {
        parent::setup();

        $room = $this->makeRoom();
        $this->user = $this->makeUser([], 100, $room);
        $this->potato = $this->makePotato();
    }

    /** @test */
    public function you_cant_drop_things_that_arent_in_your_inventory()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);
        $potato = $this->makePotato();

        $command = new DropCommand('drop potato', $user);
        $command->execute();

        $response = $command->getMessage();

        $this->assertEquals('You don\'t have a potato', $response);
        $this->assertNull($potato->owner_id);
        $this->assertNull($potato->room_id);
        $this->assertNull($potato->container_id);
    }

    /** @test */
    public function you_can_drop_items()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);
        $potato = $this->makePotato();

        $potato->giveToUser($user);
        $potato->save();

        $user->moveTo($room);
        $user->save();

        $command = new DropCommand('drop potato', $user);
        $command->execute();

        $response = $command->getMessage();

        $potato = Entity::find($potato->id);

        $this->assertEquals('You drop the Potato.', $response);
        $this->assertEquals($room->id, $potato->room_id);
    }
}
