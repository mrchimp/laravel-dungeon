<?php

namespace Tests\Unit\Dungeon\Commands;

use Dungeon\Commands\EatCommand;
use Dungeon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EatCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function you_cant_eat_things_you_cant_find()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);

        $command = new EatCommand('eat dodo', $user);
        $command->execute();

        $response = $command->getMessage();

        $this->assertStringContainsString('Could not find dodo.', $response);
    }

    /** @test */
    public function eating_things_will_affect_your_health()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 50, $room);

        $potato = $this
            ->makePotato()
            ->giveToUser($user)
            ->setHealing(50);

        $potato->save();

        $command = new EatCommand('eat potato', $user);
        $command->execute();

        $this->assertEquals(100, User::first()->getHealth());
    }

    /** @test */
    public function you_cant_eat_things_that_arent_food()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 50, $room);
        $this->makeRock()->giveToUser($user)->save();

        $command = new EatCommand('eat rock', $user);
        $command->execute();

        $response = $command->getMessage();

        $this->assertEquals('You can\'t eat that.', $response);
    }

    /** @test */
    public function you_cant_have_your_cake_and_eat_it()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 50, $room);
        $this->makePotato()->setHealing(50)->giveToUser($user)->save();

        $command = new EatCommand('eat potato', $user);
        $command->execute();

        $user->load('inventory');

        $this->assertEmpty($user->getInventory());
    }
}
