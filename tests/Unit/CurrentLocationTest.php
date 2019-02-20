<?php

namespace Tests\Unit\Dungeon;

use App\Dungeon\CurrentLocation;
use App\Dungeon\Entities\Food\Food;
use App\NPC;
use App\Room;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CurrentLocationTest extends TestCase
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

        $this->player_2 = User::create([
            'name' => 'Player 2',
            'email' => 'player2@example.com',
            'password' => bcrypt('fakepassword'),
        ]);

        $this->potato = Food::create([
            'name' => 'Potato',
            'description' => 'You can eat it.',
        ]);

        $this->npc = NPC::create([
            'name' => 'Test NPC',
            'description' => 'An NPC for testing',
        ]);

        $this->north_room = Room::create([
            'description' => 'This is the north room.',
        ]);

        $this->south_room = Room::create([
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

        $this->assertIsCollection($players);
        $this->assertCount(1, $players);
        $this->assertEquals('Player 2', $players->first()->getName());
    }

    /** @test */
    public function gets_npcs_in_room()
    {
        $npcs = $this->location->getNPCs();

        $this->assertIsCollection($npcs);
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