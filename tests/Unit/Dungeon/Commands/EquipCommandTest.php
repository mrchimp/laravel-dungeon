<?php

namespace Tests\Unit\Dungeon\Commands;

use App\Dungeon\Apparel\Apparel;
use App\Dungeon\Commands\EquipCommand;
use App\Dungeon\Entities\Food\Food;
use App\Entity;
use App\Room;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EquipCommandTest extends TestCase
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

        $this->room = Room::create([
            'description' => 'A room. Maybe with a potato in it.',
        ]);

        $this->hat = Apparel::create([
            'name' => 'Hat',
            'description' => 'Headwear',
            'equiped' => false,
        ]);

        $this->potato = Food::create([
            'name' => 'Potato',
            'description' => 'A potato.',
            'data' => [],
        ]);

        $this->command = new EquipCommand($this->user);
    }

    /** @test */
    public function apparel_can_be_equipped()
    {
        $this->hat->giveToUser($this->user)->save();

        $this->command->execute('equip hat');

        $hat = Entity::find($this->hat->id);

        $this->assertEquals(1, $hat->equiped);
        $this->assertTrue($this->command->success);
    }

    /** @test */
    public function you_cant_equip_a_potato()
    {
        $this->potato->giveToUser($this->user)->save();

        $this->command->execute('equip potato');

        $potato = Entity::find($this->potato->id);

        $this->assertEquals(0, $potato->equiped);
        $this->assertFalse($this->command->success);
    }

    /** @test */
    public function you_cant_equip_something_you_dont_have()
    {
        $this->user->moveTo($this->room)->save();
        $this->hat->moveToRoom($this->room)->save();

        $this->command->execute('equip hat');

        $hat = Entity::find($this->hat->id);

        $this->assertEquals(0, $hat->equiped);
        $this->assertFalse($this->command->success);
    }

    /** @test */
    public function you_cant_equip_something_if_you_dont_know_what_it_is()
    {
        $this->command->execute('equip the ineffable');

        $this->assertFalse($this->command->success);
    }
}