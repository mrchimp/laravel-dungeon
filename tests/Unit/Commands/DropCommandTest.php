<?php

namespace Tests\Unit\Dungeon\Commands;

use App\User;
use Dungeon\Room;
use Dungeon\Entity;
use Tests\TestCase;
use Dungeon\Entities\Food\Food;
use Dungeon\Commands\DropCommand;
use Dungeon\Entities\People\Body;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @covers \Dungeon\Commands\DropCommand
 */
class DropCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

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
    }

    /** @test */
    public function you_cant_drop_things_that_arent_in_your_inventory()
    {
        $command = new DropCommand('drop potato', $this->user);
        $command->execute();

        $response = $command->getMessage();

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

        $command = new DropCommand('drop potato', $this->user);
        $command->execute();

        $response = $command->getMessage();

        $potato = Entity::find($this->potato->id);

        $this->assertEquals('You drop the Potato.', $response);
        $this->assertEquals($this->room->id, $potato->room_id);
    }
}
