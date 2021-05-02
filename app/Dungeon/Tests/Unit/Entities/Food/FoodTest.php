<?php

namespace Tests\Unit\Entities\Food;

use Dungeon\Actions\Food\Eat;
use Dungeon\Entity;
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
        $room = $this->makeRoom();
        $user = $this->makeUser([], 50, $room);
        $potato = $this->makePotato([], 50);

        Eat::do($user, $potato);

        $this->assertEquals(100, $user->body->getHealth());
    }

    /** @test */
    public function healing_is_stored_and_retrieved_to_db()
    {
        $potato = $this->makePotato([], 23);

        $potato = Entity::find($potato->id);

        $this->assertEquals(23, $potato->consumable->hp);
    }

    /** @test */
    public function healing_is_stored_and_retrieved_when_using_finder()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);

        $potato = $this->makePotato([], 23)->giveToUser($user);
        $potato->save();

        $finder = new EntityFinder;

        $potato = $finder->find('potato', $user);

        $this->assertEquals(23, $potato->consumable->hp);
    }
}
