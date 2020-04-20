<?php

namespace Tests\Unit;

use Dungeon\Portal;
use Dungeon\Room;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function has_a_description()
    {
        $room = $this->makeRoom([
            'description' => 'This is a room.',
        ]);

        $this->assertEquals(
            'This is a room.',
            $room->getDescription()
        );
    }

    /** @test */
    public function can_have_people_in_it()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser();

        $user->moveTo($room)->save();

        $room = Room::with('contents')->first();

        $person = $room->people()->first();

        $this->assertEquals('Test User', $person->name);
    }

    /** @test */
    public function can_have_entities_in_it()
    {
        $room = $this->makeRoom();
        $potato = $this->makePotato();
        $potato->moveToRoom($room)->save();

        $room = Room::first();

        $this->assertEquals('A potato.', $room->contents->first()->getDescription());
    }

    /** @test */
    public function can_have_adjoining_room()
    {
        $start_room = $this->makeRoom();
        $other_room = $this->makeRoom();

        $other_room->setSouthExit($start_room);

        $start_room->refresh();

        $this->assertEquals($other_room->id, $start_room->north_exit->id);
    }

    /** @test */
    public function adjoined_rooms_can_have_doors()
    {
        $south_room = $this->makeRoom();
        $north_room = $this->makeRoom();

        $portal = $this->makePortal();

        $north_room->setSouthExit($south_room, [
            'portal_id' => $portal->id,
        ]);

        $south_room->refresh();
        $north_room->refresh();

        $this->assertEquals($portal->id, $south_room->north_portal->id);
        $this->assertEquals($portal->id, $north_room->south_portal->id);
    }
}
