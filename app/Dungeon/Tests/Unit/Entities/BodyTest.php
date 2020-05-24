<?php

namespace Tests\Unit\Entities;

use Dungeon\DamageTypes\MeleeDamage;
use Dungeon\Entities\People\Body;
use Dungeon\NPC;
use Dungeon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BodyTest extends TestCase
{
    use DatabaseMigrations,
        DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

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

    /** @test */
    public function can_have_health()
    {
        $this->body->setHealth(50);

        $this->assertEquals(50, $this->body->getHealth());
    }

    /** @test */
    public function can_be_hurt()
    {
        $user = factory(User::class)->create([
            'name' => 'Test User',
        ]);

        $this->body->setHealth(100);

        $this->body->hurt(50, MeleeDamage::class);

        $this->assertEquals(50, $this->body->getHealth());
    }

    /** @test */
    public function can_die()
    {
        $this->body->setHealth(100);

        $this->assertEquals(true, $this->body->isAlive());

        $this->body->hurt(100, MeleeDamage::class);

        $this->assertEquals(false, $this->body->isAlive());
    }

    /** @test */
    public function when_killed_the_user_is_detached()
    {
        $user = factory(User::class)->create([
            'name' => 'Test User',
        ]);

        $this->body->setHealth(100);
        $this->body->giveToUser($user)->save();
        $this->body->hurt(100, MeleeDamage::class);

        $body = Body::first();

        $this->assertNull($body->owner);
    }

    /** @test */
    public function if_alive_cannot_be_looted()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function if_dead_can_be_looted()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function the_body_should_inherit_the_owners_name()
    {
        $user = factory(User::class)->create([
            'name' => 'Geoff',
        ]);

        $body = factory(Body::class)->create();

        $body->giveToUser($user)->save();

        $this->assertEquals('Geoff', $body->getName());
    }
}
