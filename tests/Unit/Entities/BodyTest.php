<?php

namespace Tests\Unit\Entities;

use App\User;
use Dungeon\Entities\People\Body;
use Dungeon\NPC;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @covers \Dungeon\Entities\People\Body
 */
class BodyTest extends TestCase
{
    use DatabaseMigrations,
        DatabaseTransactions;

    public function setup()
    {
        parent::setup();

        $this->body = factory(Body::class)->create([
            'name' => 'Test body',
        ]);
    }

    /** @test */
    public function can_have_user_attached()
    {
        $user = factory(User::class)->create([
            'name' => 'Test User',
        ]);

        $this->body->giveToUser($user)->save();

        $the_user = $this->body->owner;

        $this->assertEquals($user->id, $the_user->id);
    }

    /** @test */
    public function can_have_npc_attached()
    {
        $npc = factory(NPC::class)->create([
            'name' => 'Test NPC',
        ]);

        $this->body->giveToNPC($npc);

        $the_npc = $this->body->npc;

        $this->assertEquals($npc->id, $the_npc->id);
    }

    public function can_have_health()
    {
        $this->body->setHealth(50);

        $health = $this->body->getHealth();
        $this->assertEquals(50, $this->body->getHealth());
    }

    public function can_be_hurt()
    {
        $user = factory(User::class)->create([
            'name' => 'Test User',
        ]);

        $this->body->setHealth(100);

        $this->body->hurt(50);
        $health = $this->body->getHealth();

        $this->assertEquals(50, $health);
    }

    public function can_die()
    {
        $this->body->setHealth(100);

        $this->assert(true, $this->body->isAlive());

        $this->body->hurt(100);

        $this->assert(false, $this->body->isAlive());
    }

    public function when_killed_the_user_is_detached()
    {
        $user = factory(User::class)->create([
            'name' => 'Test User',
        ]);

        $this->body->setHealth(100);
        $this->body->giveToUser($user)->save();
        $this->body->hurt(100);

        $body = Body::first();

        $this->assertNull($body->owner);
    }

    public function if_alive_cannot_be_looted()
    {
        $this->markTestIncomplete();
    }

    public function if_dead_can_be_looted()
    {
        $this->markTestIncomplete();
    }

    public function the_body_should_inherit_the_owners_name()
    {
        $this->markTestIncomplete();
    }
}
