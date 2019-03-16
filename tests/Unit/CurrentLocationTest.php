<?php

namespace Tests\Unit\Dungeon;

use App\User;
use Dungeon\NPC;
use Dungeon\Room;
use Tests\TestCase;
use Dungeon\CurrentLocation;
use Dungeon\Entities\Food\Food;
use Dungeon\Entities\People\Body;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Dungeon\Collections\EntityCollection;

class CurrentLocationTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function setup()
    {
        parent::setup();

        $this->user = factory(User::class)->create([
            'name' => 'Test User',
        ]);
        $user_body = factory(Body::class)->create();
        $user_body->giveToUser($this->user)->save();

        $this->player_2 = factory(User::class)->create([
            'name' => 'Player 2',
        ]);
        $player_2_body = factory(Body::class)->create();
        $player_2_body->giveToUser($this->player_2)->save();

        $this->potato = factory(Food::class)->create([
            'name' => 'Potato',
            'description' => 'You can eat it.',
        ]);

        $this->npc = factory(NPC::class)->create([
            'name' => 'Test NPC',
            'description' => 'An NPC for testing',
        ]);

        $npc_body = factory(Body::class)->create();
        $npc_body->giveToNPC($this->npc)->save();

        $this->north_room = factory(Room::class)->create([
            'description' => 'This is the north room.',
        ]);

        $this->south_room = factory(Room::class)->create([
            'description' => 'This is the south room.',
        ]);

        $this->user->moveTo($this->north_room)->save();
        $this->player_2->moveTo($this->north_room)->save();
        $this->npc->moveTo($this->north_room)->save();
        $this->potato->moveToRoom($this->north_room)->save();
        $this->north_room->setSouthExit($this->south_room)->save();

        $this->location = new CurrentLocation($this->user);
    }

    /** @test */
    public function gets_players_in_room()
    {
        $players = $this->location->getPlayers();

        $this->assertIsEntityCollection($players);
        $this->assertCount(1, $players);
        $this->assertEquals('Player 2', $players->first()->getName());
    }

    /** @test */
    public function gets_npcs_in_room()
    {
        $npcs = $this->location->getNPCs();

        $this->assertIsEntityCollection($npcs);
        $this->assertCount(1, $npcs);
        $this->assertEquals('Test NPC', $npcs->first()->getName());
    }

    /** @test */
    public function gets_items_in_room()
    {
        $items = $this->location->getItems();

        $this->assertIsEntityCollection($items);
        $this->assertCount(1, $items);
        $this->assertEquals('Potato', $items->first()->getName());
    }

    /** @test */
    public function gets_exits_in_room()
    {
        $exits = $this->location->getExits();

        $this->assertIsCollection($exits);
        $this->assertCount(1, $exits);
        $this->assertEquals('This is the south room.', $exits->first()->getDescription());
    }
}
