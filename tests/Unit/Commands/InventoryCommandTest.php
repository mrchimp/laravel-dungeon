<?php

namespace Tests\Unit\Dungeon\Commands;

use Dungeon\Commands\InventoryCommand;
use Dungeon\Entities\Food\Food;
use App\Room;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

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

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('fakepassword'),
        ]);

        $this->room = Room::create([
            'description' => 'A room. Maybe with a potato in it.',
        ]);

        $this->potato = Food::create([
            'name' => 'Potato',
            'description' => 'A potato.',
            'data' => [],
        ]);

        $this->banana = Food::create([
            'name' => 'Banana',
            'description' => 'A banana.',
            'data' => [],
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