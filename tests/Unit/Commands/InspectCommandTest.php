<?php

namespace Tests\Unit\Dungeon\Commands;

use Dungeon\User;
use Dungeon\Room;
use Dungeon\Entity;
use Tests\TestCase;
use Dungeon\Entities\Food\Food;
use Dungeon\Entities\People\Body;
use Dungeon\Commands\InspectCommand;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @covers \Dungeon\Commands\InspectCommand
 */
class InspectCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function setup()
    {
        parent::setup();

        $this->user = factory(User::class)->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('fakepassword'),
        ]);

        $this->body = factory(Body::class)->create();
        $this->body->giveToUser($this->user)->save();

        $this->room = factory(Room::class)->create([
            'description' => 'A room. Maybe with a potato in it.',
        ]);

        $this->potato = factory(Food::class)->create([
            'name' => 'Potato',
            'description' => 'A potato.',
            'data' => [],
        ]);

        $this->box = factory(Entity::class)->create([
            'name' => 'Box',
            'description' => 'You can put things in it.',
            'class' => Entity::class,
            'data' => [],
        ]);

        $this->user->moveTo($this->room);
        $this->user->save();
    }

    /** @test */
    public function you_cant_inspect_things_that_you_cant_find()
    {
        $this->command = new InspectCommand('inspect atlantis', $this->user);
        $this->command->execute();
        $response = $this->command->getMessage();

        $this->assertStringContainsString('Could not find atlantis.', $response);
    }

    /** @test */
    public function you_can_inspect_things_in_your_inventory()
    {
        $this->potato->giveToUser($this->user);
        $this->potato->save();

        $this->command = new InspectCommand('inspect potato', $this->user);
        $this->command->execute();
        $response = $this->command->getMessage();

        $this->assertStringContainsString('A potato.', $response);
    }

    /** @test */
    public function you_can_inspect_things_in_the_room()
    {
        $this->potato->moveToRoom($this->room);
        $this->potato->save();

        $this->command = new InspectCommand('inspect potato', $this->user);
        $this->command->execute();
        $response = $this->command->getMessage();

        $this->assertStringContainsString('A potato.', $response);
    }

    /** @test */
    public function you_can_inspect_things_in_containers()
    {
        $this->potato->moveToContainer($this->box);
        $this->potato->save();

        $this->box->moveToRoom($this->room);
        $this->box->save();

        $this->command = new InspectCommand('inspect potato', $this->user);
        $this->command->execute();
        $response = $this->command->getMessage();

        $this->assertStringContainsString('A potato.', $response);
    }

    /** @test */
    public function you_can_see_the_contents_of_a_container_when_inspecting_it()
    {
        $this->potato->moveToContainer($this->box);
        $this->potato->save();

        $this->box->moveToRoom($this->room);
        $this->box->save();

        $this->command = new InspectCommand('inspect box', $this->user);
        $this->command->execute();
        $response = $this->command->getMessage();

        $this->assertStringContainsString('Potato', $response);
    }
}
