<?php

namespace Tests\Unit;

use Dungeon\NPC;
use Dungeon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ApparelTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function users_can_wear_apparel()
    {
        $user = $this->makeUser();
        $hat = $this->makeHat()->giveToUser($user);
        $hat->equipable->equip()->save();
        $hat->save();

        $user = User::first();

        $hat = $user->getEquiped()->first();

        $this->assertEquals('Hat', $hat->getName());
    }

    /** @test */
    public function npcs_can_wear_apparel()
    {
        $npc = $this->makeNPC();
        $hat = $this->makeHat()->giveToNPC($npc);
        $hat->equipable->equip()->save();
        $hat->save();
        $npc->body->load('inventory');
        $npc = NPC::first();

        $hat = $npc->getEquiped()->first();

        $this->assertEquals('Hat', $hat->getName());
    }
}
