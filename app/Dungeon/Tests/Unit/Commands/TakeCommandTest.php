<?php

namespace Tests\Unit\Commands;

use Dungeon\Actions\Entities\Hurt;
use Dungeon\Commands\TakeCommand;
use Dungeon\DamageTypes\MeleeDamage;
use Dungeon\Entity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TakeCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function cant_take_things_you_already_have()
    {
        $user = $this->makeUser();
        $potato = $this->makePotato()->giveToUser($user);
        $potato->save();

        $command = new TakeCommand('take potato', $user);
        $command->execute();

        $potato = Entity::find($potato->id);

        $this->assertEquals($user->id, $potato->container->owner_id);
    }

    /** @test */
    public function cant_take_things_that_are_untakeable()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);
        $potato = $this->makePotato()->moveToRoom($room);
        $potato->can_be_taken = false;
        $potato->save();

        $command = new TakeCommand('take potato', $user);
        $command->execute();

        $potato = Entity::find($potato->id);

        $this->assertNull($potato->owner_id);
    }

    /** @test */
    public function can_take_things_from_the_room_youre_in()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);
        $potato = $this->makePotato()->moveToRoom($room);
        $potato->save();

        $command = new TakeCommand('take potato', $user);
        $command->execute();

        $potato = Entity::find($potato->id);

        $this->assertEquals($user->body->id, $potato->container_id);
    }

    /** @test */
    public function can_take_things_from_containers_in_the_room()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);
        $box = $this->makeBox();
        $potato = $this->makePotato()->moveToContainer($box);
        $potato->save();

        $box->moveToRoom($room);
        $box->save();

        $command = new TakeCommand('take potato', $user);
        $command->execute();

        $potato = Entity::find($potato->id);

        $this->assertEquals($user->body->id, $potato->container_id);
    }

    /** @test */
    public function you_cant_take_things_that_dont_exist()
    {
        $user = $this->makeUser();

        $command = new TakeCommand('take avocado', $user);
        $command->execute();

        $this->assertEquals('Take what?', $command->getMessage());
    }

    /** @test */
    public function you_can_loot_dead_bodies()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);
        $enemy = $this->makeUser([
            'name' => 'dave',
        ], 100, $room);
        $potato = $this->makePotato();
        $potato->giveToUser($enemy)->save();

        Hurt::do($enemy->body, 99999, MeleeDamage::class);

        $command = new TakeCommand('take potato from dave', $user);
        $command->execute();

        $potato->refresh();

        $this->assertTrue($command->success);
        $this->assertEquals($user->body->id, $potato->container->id);
    }

    /** @test */
    public function you_cant_loot_living_bodies()
    {
        $this->markTestIncomplete();
    }
}
