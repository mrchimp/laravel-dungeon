<?php

namespace Tests\Unit\Entities;

use Dungeon\Tests\TestEntity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EntityTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function entities_can_be_converted_to_arrays()
    {
        $potato = $this->makePotato([
            'name' => 'Potato',
            'description' => 'A potato.',
        ]);

        $array = $potato->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('Potato', $array['name']);
        $this->assertEquals('A potato.', $array['description']);
    }
}
