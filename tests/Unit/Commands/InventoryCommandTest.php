<?php

namespace Tests\Unit\Dungeon\Commands;

use Dungeon\User;
use Dungeon\Room;
use Tests\TestCase;
use Dungeon\Entities\Food\Food;
use Dungeon\Entities\People\Body;
use Dungeon\Commands\InventoryCommand;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @covers \Dungeon\Commands\InventoryCommand
 */
class InventoryCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    protected $user;

    protected $room;

    protected $potato;

    protected $banana;

    protected $command;

    public function setup()
    {
        parent::setup();

        $this->user = factory(User::class)->create([
            'name' => 'Test User',
        ]);

        $this->body = factory(Body::class)->create();
        $this->body->giveToUser($this->user)->save();

        $this->room = factory(Room::class)->create([
            'description' => 'A room. Maybe with a potato in it.',
        ]);

        $this->potato = factory(Food::class)->create([
            'name' => 'Potato',
            'description' => 'A potato.',
        ]);

        $this->banana = factory(Food::class)->create([
            'name' => 'Banana',
            'description' => 'A banana.',
        ]);
    }

    /** @test */
    public function you_can_see_what_is_in_your_inventory()
    {
        $this->potato->giveToUser($this->user);
        $this->potato->save();

        $this->user->moveTo($this->room);
        $this->user->save();

        $this->banana->moveToRoom($this->room);
        $this->banana->save();

        $command = new InventoryCommand('inventory', $this->user);
        $command->execute();
        $response = $command->getMessage();

        $this->assertStringContainsString('Potato', $response);
        $this->assertStringNotContainsString('Banana', $response);
    }
}
