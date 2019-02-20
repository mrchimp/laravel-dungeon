<?php

namespace Tests\Unit\Dungeon\Entities;

use Dungeon\Entities\Food\Food;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EntityTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function setup()
    {
        parent::setup();

        $this->entity = Food::create([
            'name' => 'Test Entity',
            'description' => 'An Entity for testing purposes.',
        ]);
        $this->entity->setHealing(10);
    }

    /** @test */
    public function entities_can_be_converted_to_arrays()
    {
        $array = $this->entity->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('Test Entity', $array['name']);
        $this->assertEquals('An Entity for testing purposes.', $array['description']);
    }

    /** @test */
    public function when_converted_to_arrays_output_will_include_serializable_attributes()
    {
        $array = $this->entity->toArray();

        $this->assertArrayHasKey('healing', $array);
    }
}