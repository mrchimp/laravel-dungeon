<?php

namespace Tests\Feature;

use Dungeon\Room;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function setup()
    {
        parent::setup();

        $this->user = new User([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'sercretmagicword',
        ]);
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

    /** @test */
    public function health_can_be_set()
    {
        $this->user->setHealth(66);

        $this->assertEquals(66, $this->user->getHealth());
    }

    /** @test */
    public function health_is_stored_and_retrieved()
    {
        $this->user->setHealth(50);
        $this->user->save();

        $user = User::first();

        $this->assertEquals(50, $user->getHealth());
    }

    /** @test */
    public function can_be_in_a_room()
    {
        $room = Room::create([
            'description' => 'This is a room.',
        ]);

        $this->user->moveTo($room);
        $this->user->save();

        $user = User::with('room')->first();

        $this->assertEquals('This is a room.', $user->room->getDescription());
    }
}