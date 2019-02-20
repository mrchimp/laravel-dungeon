<?php

namespace Tests\Unit\Dungeon\Entities\Food;

use Dungeon\EntityFinder;
use Dungeon\Entities\Food\Food;
use Dungeon\Entity;
use Dungeon\Room;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FoodTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function setup()
    {
        parent::setup();

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('fakepassword'),
        ]);
        $this->user->setHealth(50)->save();

        $this->food = Food::create([
            'name' => 'Potato',
            'description' => 'A potato.',
        ]);
        $this->food->setHealing(10);

        $this->room = Room::create([
            'name' => 'A room.',
            'description' => 'A space for things.',
        ]);
    }

    /** @test */
    public function eating_heals_consumer()
    {
        $this->food->eat($this->user);
        $this->assertEquals(60, $this->user->getHealth());
    }

    /** @test */
    public function healing_is_stored_and_retrieved()
    {
        $this->food->setHealing(23);
        $this->food->save();

        $food = Food::first();

        $this->assertEquals(23, $food->healing);
    }

    /** @test */
    public function healing_is_stored_and_retrieved_when_using_finder()
    {
        $this->user->moveTo($this->room);
        $this->user->save();

        $this->food->setHealing(23);
        $this->food->giveToUser($this->user);
        $this->food->save();

        $finder = new EntityFinder;

        $food = $finder->find('potato', $this->user);

        $this->assertEquals(23, $food->healing);
    }
}