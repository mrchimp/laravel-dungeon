<?php

namespace Tests\Unit\Dungeon\Entities\NPCs;

use App\NPC;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class NPCTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function setup()
    {
        parent::setup();

        NPC::create([
            'name' => 'Test NPC',
            'description' => 'An NPC for testing.',
        ]);

        $this->npc = NPC::first();
    }

    /** @test */
    public function npcs_have_names()
    {
        $name = $this->npc->getName();

        $this->assertEquals('Test NPC', $name);
    }

    /** @test */
    public function npcs_have_descriptions()
    {
        $description = $this->npc->getDescription();

        $this->assertEquals('An NPC for testing.', $description);
    }
}