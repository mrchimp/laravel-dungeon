<?php

namespace Tests\Unit\Entities\Food;

use Dungeon\Entities\Food\Food;
use Dungeon\EntityFinder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FoodTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function eating_heals_consumer()
    {
        $user = $this->makeUser([], 50);
        $potato = $this->makePotato([], 50);

        $potato->eat($user);

        $this->assertEquals(100, $user->getHealth());
    }

    /** @test */
    public function healing_is_stored_and_retrieved_to_db()
    {
        $potato = $this->makePotato([], 50);
        $potato->setHealing(23)->save();

        $potato = Food::find($potato->id);

        $this->assertEquals(23, $potato->healing);
    }

    /** @test */
    public function healing_is_stored_and_retrieved_when_using_finder()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);

        $potato = $this->makePotato()->setHealing(23)->giveToUser($user);
        $potato->save();

        $finder = new EntityFinder;

        $potato = $finder->find('potato', $user);

        $this->assertEquals(23, $potato->healing);
    }
}
