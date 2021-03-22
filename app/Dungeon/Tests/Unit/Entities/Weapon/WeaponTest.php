<?php

namespace Tests\Unit\Entities\Weapon;

use Dungeon\Entity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WeaponTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function damage_types_are_stored_and_restored_when_saved_to_database_using_create()
    {
        // Create a new weapon with damage_types serializable attribute
        $weapon = Entity::create([
            'name' => 'Rock',
            'description' => 'You can hit people with it.',
        ])
        ->makeWeapon([
            'blunt' => 50,
        ]);

        $this->assertIsArray($weapon->damageTypes());
        $this->assertEquals(50, $weapon->weapon->blunt);

        // After restoring the weapon from the database it should still
        // have the serializable attributes applied
        $weapon = Entity::where('name', 'Rock')->first();

        $this->assertEquals(50, $weapon->weapon->blunt);
    }

    /** @test */
    public function damage_types_are_stored_and_restored_when_saved_to_database_using_make()
    {
        // Create a new weapon with damage_types serializable attribute
        $weapon = Entity::create([
            'name' => 'Rock',
            'description' => 'You can hit people with it.',
        ])
        ->makeWeapon([
            'blunt' => 50,
        ]);

        $this->assertIsArray($weapon->damageTypes());
        $this->assertEquals(50, $weapon->weapon->blunt);

        $weapon->save();

        // After restoring the weapon from the database it should still
        // have the serializable attributes applied
        $weapon = Entity::where('name', 'Rock')->first();

        $this->assertEquals(50, $weapon->weapon->blunt);
    }

    /** @test */
    public function damage_types_are_stored_and_restored_when_saved_to_database_using_new()
    {
        // Create a new weapon with damage_types serializable attribute
        $weapon = Entity::create([
            'name' => 'Rock',
            'description' => 'You can hit people with it.',
        ])
        ->makeWeapon([
            'blunt' => 50,
        ]);

        $this->assertIsArray($weapon->damageTypes());
        $this->assertEquals(50, $weapon->weapon->blunt);

        $weapon->save();

        // After restoring the weapon from the database it should still
        // have the serializable attributes applied
        $weapon = Entity::where('name', 'Rock')->first();

        $this->assertEquals(50, $weapon->weapon->blunt);
    }
}
