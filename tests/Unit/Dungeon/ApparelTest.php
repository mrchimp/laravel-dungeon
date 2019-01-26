<?php

namespace Tests\Unit\Dungeon;

use App\Dungeon\Apparel\Apparel;
use App\NPC;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ApparelTest extends TestCase
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

        $this->npc = NPC::create([
            'name' => 'Test NPC',
            'description' => 'An NPC for testing.',
        ]);

        $this->hat = Apparel::create([
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