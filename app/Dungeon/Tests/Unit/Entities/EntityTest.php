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

    /** @test */
    public function when_converted_to_arrays_output_will_include_serializable_attributes()
    {
        $item = TestEntity::create([
            'name' => 'Test Entity',
            'description' => 'just for testing',
            'test_value' => 'Banana'
        ]);

        $array = $item->toArray();
dd($array);
        $this->assertArrayHasKey('banana', $array);
    }
}
