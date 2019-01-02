<?php

namespace Tests\Unit\Dungeon\Entities\Food;

use App\User;
use Tests\TestCase;
use App\Dungeon\Entities\Food\Food;

class FoodTest extends TestCase
{
    public function setup()
    {
        parent::setup();

        $this->user = new User();
        $this->user->setHealth(50);
        
        $this->food = new Food();
        $this->food->setHealing(10);
    }

    /** @test */
    public function eating_heals_consumer()
    {   
        $this->food->eat($this->user);
        $this->assertEquals(60, $this->user->getHealth());
    }
}