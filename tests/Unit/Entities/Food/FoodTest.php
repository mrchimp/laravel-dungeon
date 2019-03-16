<?php

namespace Tests\Unit\Dungeon\Entities\Food;

use App\User;
use Dungeon\Room;
use Tests\TestCase;
use Dungeon\EntityFinder;
use Dungeon\Entities\Food\Food;
use Dungeon\Entities\People\Body;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FoodTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function setup()
    {
        parent::setup();

        $this->user = factory(User::class)->create([
            'name' => 'Test User',
        ]);

        $body = factory(Body::class)->create();
        $body->giveToUser($this->user)->save();
        $this->user->setHealth(50)->save();

        $this->food = factory(Food::class)->create([
            'name' => 'Potato',
            'description' => 'A potato.',
        ]);
        $this->food->setHealing(10);

        $this->room = factory(Room::class)->create([
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
    public function healing_is_stored_and_retrieved_to_db()
    {
        $this->food->setHealing(23);
        $this->food->save();

        $food = Food::find($this->food->id);

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
