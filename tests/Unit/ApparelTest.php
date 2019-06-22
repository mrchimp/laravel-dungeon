<?php

namespace Tests\Unit\Dungeon;

use Dungeon\Apparel\Apparel;
use Dungeon\NPC;
use Dungeon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @covers \Dungeon\Apparel\Apparel
 */
class ApparelTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function setup()
    {
        parent::setup();

        $this->user = factory(User::class)->create([
            'name' => 'Test User',
        ]);

        $this->npc = factory(NPC::class)->create([
            'name' => 'Test NPC',
            'description' => 'An NPC for testing.',
        ]);

        $this->hat = factory(Apparel::class)->create([
            'name' => 'Hat',
            'description' => 'You can wear it on yer head.',
        ]);
    }

    /** @test */
    public function users_can_wear_apparel()
    {
        $this->hat->giveToUser($this->user);
        $this->hat->wear();
        $this->hat->save();

        $user = User::first();

        $hat = $user->getApparel()->first();

        $this->assertEquals('Hat', $hat->getName());
    }

    /** @test */
    public function npcs_can_wear_apparel()
    {
        $this->hat->giveToNPC($this->npc);
        $this->hat->wear();
        $this->hat->save();

        $npc = NPC::first();

        $hat = $npc->getApparel()->first();

        $this->assertEquals('Hat', $hat->getName());
    }
}
