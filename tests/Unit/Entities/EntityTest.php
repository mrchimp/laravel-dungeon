<?php

namespace Tests\Unit\Entities;

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
        $potato = $this->makePotato([], 50);

        $array = $potato->toArray();

        $this->assertArrayHasKey('healing', $array);
    }
}
