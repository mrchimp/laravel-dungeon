<?php

namespace Tests\Unit\Dungeon\Commands;

use App\Dungeon\Commands\DropCommand;
use App\Dungeon\Entities\Food\Food;
use App\Entity;
use App\Room;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DropCommandTest extends TestCase
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

        $this->potato = Food::create([
            'name' => 'Potato',
            'description' => 'A potato.',
            'data' => [],
        ]);

        $this->command = new DropCommand($this->user);
    }

    /** @test */
    public function you_cant_drop_things_that_arent_in_your_inventory()
    {
        $this->command->execute('drop potato');
        $response = $this->command->getMessage();

        $this->assertEquals('You don\'t have a potato', $response);
        $this->assertNull($this->potato->owner_id);
        $this->assertNull($this->potato->room_id);
        $this->assertNull($this->potato->container_id);
    }

    /** @test */
    public function you_can_drop_items()
    {
        $this->potato->giveToUser($this->user);
        $this->potato->save();

        $this->user->moveTo($this->room);
        $this->user->save();

        $this->command->execute('drop potato');
        $response = $this->command->getMessage();

        $potato = Entity::find($this->potato->id);

        $this->assertEquals('You drop the Potato.', $response);
        $this->assertEquals($this->room->id, $potato->room_id);
    }
}