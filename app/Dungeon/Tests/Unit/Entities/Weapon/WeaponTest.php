<?php

namespace Tests\Unit\Entities\Weapon;

use Dungeon\DamageTypes\MeleeDamage;
use Dungeon\Entities\Weapons\Weapon;
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
        $weapon = Weapon::create([
            'name' => 'Rock',
            'description' => 'You can hit people with it.',
            'damage_types' => [
                MeleeDamage::class => 50,
            ],
        ]);

        $this->assertIsArray($weapon->damageTypes());
        $this->assertEquals(50, $weapon->damageType(MeleeDamage::class));

        // After restoring the weapon from the database it should still
        // have the serializable attributes applied
        $weapon = Weapon::where('name', 'Rock')->first();

        $this->assertEquals(50, $weapon->damageType(MeleeDamage::class));
    }

    /** @test */
    public function damage_types_are_stored_and_restored_when_saved_to_database_using_make()
    {
        // Create a new weapon with damage_types serializable attribute
        $weapon = Weapon::make([
            'name' => 'Rock',
            'description' => 'You can hit people with it.',
            'damage_types' => [
                MeleeDamage::class => 50,
            ],
        ]);

        $this->assertIsArray($weapon->damageTypes());
        $this->assertEquals(50, $weapon->damageType(MeleeDamage::class));

        $weapon->save();

        // After restoring the weapon from the database it should still
        // have the serializable attributes applied
        $weapon = Weapon::where('name', 'Rock')->first();

        $this->assertEquals(50, $weapon->damageType(MeleeDamage::class));
    }

    /** @test */
    public function damage_types_are_stored_and_restored_when_saved_to_database_using_new()
    {
        // Create a new weapon with damage_types serializable attribute
        $weapon = new Weapon([
            'name' => 'Rock',
            'description' => 'You can hit people with it.',
            'damage_types' => [
                MeleeDamage::class => 50,
            ],
        ]);

        $this->assertIsArray($weapon->damage_types);
        $this->assertEquals(50, $weapon->damage_types[MeleeDamage::class]);

        $weapon->save();

        // After restoring the weapon from the database it should still
        // have the serializable attributes applied
        $weapon = Weapon::where('name', 'Rock')->first();

        $this->assertEquals(50, $weapon->damageType(MeleeDamage::class));
    }
}
