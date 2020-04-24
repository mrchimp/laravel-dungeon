<?php

namespace Tests\Unit;

use Dungeon\Entities\People\Body;
use Dungeon\Room;
use Dungeon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create([
            'name' => 'Test User',
        ]);

        $this->body = factory(Body::class)->create();
        $this->body->giveToUser($this->user)->save();
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
        $this->user->body->save();

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
        $this->user->body->save();

        $user = User::first();

        $this->assertEquals('This is a room.', $user->getRoom()->getDescription());
    }

    /** @test */
    public function can_have_a_body()
    {
        $body = factory(Body::class)->create();
        $user = factory(User::class)->create();

        $body->giveToUser($user)->save();

        $this->assertEquals($body->id, $user->body->id);
    }

    /** @test */
    public function if_user_doesnt_have_a_body_then_getRoom_returns_null()
    {
        $user = factory(User::class)->create();

        $this->assertNull($user->getRoom());
    }

    /** @test */
    public function getName_gets_the_name()
    {
        $user = factory(User::class)->create([
            'name' => 'Mr Testman',
        ]);

        $this->assertEquals('Mr Testman', $user->getName());
    }
}
