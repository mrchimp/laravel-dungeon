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
