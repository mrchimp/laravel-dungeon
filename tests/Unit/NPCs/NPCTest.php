<?php

namespace Tests\Unit\Dungeon\Entities\NPCs;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class NPCTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function npcs_have_names()
    {
        $npc = $this->makeNPC([
            'name' => 'Test NPC',
        ]);

        $this->assertEquals('Test NPC', $npc->getName());
    }

    /** @test */
    public function npcs_have_descriptions()
    {
        $npc = $this->makeNPC([
            'description' => 'An NPC for testing.',
        ]);

        $this->assertEquals('An NPC for testing.', $npc->getDescription());
    }
}
