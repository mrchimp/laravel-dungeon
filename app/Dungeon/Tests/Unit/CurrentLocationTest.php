<?php

namespace Tests\Unit;

use Dungeon\CurrentLocation;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CurrentLocationTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function gets_players_in_room()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([
            'name' => 'Gordon',
        ], 100, $room);
        $this->makeUser([
            'name' => 'Larry',
        ], 100, $room);

        $location = new CurrentLocation($user);

        $players = $location->getPlayers();

        $this->assertIsEntityCollection($players);
        $this->assertCount(1, $players);
        $this->assertEquals('Larry', $players->first()->getName());
    }

    /** @test */
    public function gets_npcs_in_room()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);
        $this->makeNPC([], 100, $room);

        $location = new CurrentLocation($user);

        $npcs = $location->getNPCs();

        $this->assertIsEntityCollection($npcs);
        $this->assertCount(1, $npcs);
        $this->assertEquals('Test NPC', $npcs->first()->getName());
    }

    /** @test */
    public function gets_items_in_room()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);
        $potato = $this->makePotato([
            'name' => 'Potato',
        ]);
        $potato->moveToRoom($room)->save();

        $location = new CurrentLocation($user);

        $items = $location->getItems();

        $this->assertIsEntityCollection($items);
        $this->assertCount(1, $items);
        $this->assertEquals('Potato', $items->first()->getName());
    }

    /** @test */
    public function gets_exits_in_room()
    {
        $north_room = $this->makeRoom();
        $south_room = $this->makeRoom([
            'description' => 'This is the south room.',
        ]);
        $user = $this->makeUser([], 100, $north_room);

        $north_room->setSouthExit($south_room)->save();

        $location = new CurrentLocation($user);

        $exits = $location->getExits();

        $this->assertIsCollection($exits);
        $this->assertCount(1, $exits);
        $this->assertEquals('This is the south room.', $exits->first()->getDescription());
    }
}
