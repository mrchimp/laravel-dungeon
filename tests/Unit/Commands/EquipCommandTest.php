<?php

namespace Tests\Unit\Dungeon\Commands;

use Dungeon\Apparel\Apparel;
use Dungeon\Commands\EquipCommand;
use Dungeon\Entities\Food\Food;
use Dungeon\Entity;
use Dungeon\Room;
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
    }

    /** @test */
    public function apparel_can_be_equipped()
    {
        $this->user->moveTo($this->room)->save();
        $this->hat->giveToUser($this->user)->save();

        $command = new EquipCommand('equip hat', $this->user);
        $command->execute();

        $hat = Entity::find($this->hat->id);

        $this->assertEquals(1, $hat->equiped);
        $this->assertTrue($command->success);
    }

    /** @test */
    public function you_cant_equip_a_potato()
    {
        $this->potato->giveToUser($this->user)->save();

        $command = new EquipCommand('equip potato', $this->user);
        $command->execute();

        $potato = Entity::find($this->potato->id);

        $this->assertEquals(0, $potato->equiped);
        $this->assertFalse($command->success);
    }

    /** @test */
    public function you_cant_equip_something_you_dont_have()
    {
        $this->user->moveTo($this->room)->save();
        $this->hat->moveToRoom($this->room)->save();

        $command = new EquipCommand('equip hat', $this->user);
        $command->execute();

        $hat = Entity::find($this->hat->id);

        $this->assertEquals(0, $hat->equiped);
        $this->assertFalse($command->success);
    }

    /** @test */
    public function you_cant_equip_something_if_you_dont_know_what_it_is()
    {
        $command = new EquipCommand('equip the ineffable', $this->user);
        $command->execute();

        $this->assertFalse($command->success);
    }
}