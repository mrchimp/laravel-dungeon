<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function setup()
    {
        parent::setup();

        $this->user = new User();
    }

    /** @test */
    public function has_health()
    {
        $this->assertEquals(100, $this->user->getHealth());
    }

    /** @test */
    public function can_be_hurt()
    {
        $this->user->hurt(50);

        $this->assertEquals(50, $this->user->getHealth());
    }

    /** @test */
    public function can_be_healed()
    {
        $this->user->hurt(50);
        $this->user->heal(25);

        $this->assertEquals(75, $this->user->getHealth());
    }

    /** @test */
    public function can_be_killed()
    {
        $this->user->hurt(150);

        $this->assertTrue($this->user->isDead());
    }
}