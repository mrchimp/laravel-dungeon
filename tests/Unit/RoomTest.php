<?php

namespace Tests\Feature;

use Dungeon\Entities\Food\Food;
use Dungeon\Entities\People\Body;
use Dungeon\Room;
use Dungeon\User;
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
}
