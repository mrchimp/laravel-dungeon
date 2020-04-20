<?php

namespace Tests\Unit;

use Dungeon\CurrentLocation;
use Dungeon\Portal;
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
        $north_room = $this->makeRoom([
            'description' => 'The North room'
        ]);
        $south_room = $this->makeRoom([
            'description' => 'The South room.',
        ]);
        $west_room = $this->makeRoom([
            'description' => 'The West room.',
        ]);

        // Door between south and west rooms
        $south_west_portal = $this->makePortal([
            'name' => 'Metal door',
            'description' => 'A big metal door with a keyhole.',
        ]);

        $south_room->setNorthExit($north_room)->save();

        $south_room->setWestExit($west_room, [
            'portal_id' => $south_west_portal->id
        ])->save();

        $south_room->refresh();

        $user = $this->makeUser([], 100, $south_room);

        $location = new CurrentLocation($user);

        $exits = $location->getExits();

        $this->assertIsCollection($exits);
        $this->assertCount(4, $exits);
        $this->assertEquals('A big metal door with a keyhole.', $exits->get('west')->getDescription());
        $this->assertEquals('Metal door', $exits->get('west')->getName());
        $this->assertEquals('Looks like you can go that way.', $exits->get('north')->getDescription());
        $this->assertEquals('A way out', $exits->get('north')->getName());
        $this->assertNull($exits->get('south'));
        $this->assertNull($exits->get('east'));
    }
}
