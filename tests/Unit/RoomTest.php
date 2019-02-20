<?php

namespace Tests\Feature;

use Dungeon\Entities\Food\Food;
use Dungeon\Room;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    protected $room;

    public function setup()
    {
        parent::setup();

        $this->room = new Room([
            'description' => 'This is a room.',
        ]);
    }

    /** @test */
    public function has_a_description()
    {
        $this->assertEquals(
            'This is a room.',
            $this->room->getDescription()
        );
    }

    /** @test */
    public function can_have_people_in_it()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secretmagicword',
        ]);

        $this->room->save();
        $this->room->people()->save($user);

        $room = Room::with('people')->first();

        $person = $room->people->first();

        $this->assertEquals('Test User', $person->name);
    }

    /** @test */
    public function can_have_entities_in_it()
    {
        $room = Room::create([
            'description' => 'This room has something in it.',
        ]);

        $potato = Food::create([
            'name' => 'Potato',
            'description' => 'A potato.',
        ]);

        $potato->moveToRoom($room);

        $potato->save();
        $room->save();

        $room = Room::first();

        $this->assertEquals('A potato.', $room->contents->first()->getDescription());
    }
}
