<?php

namespace Tests\Feature;

use App\User;
use Dungeon\Room;
use Tests\TestCase;
use Dungeon\Entities\Food\Food;
use Dungeon\Entities\People\Body;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoomTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    protected $room;

    public function setup()
    {
        parent::setup();

        $this->room = factory(Room::class)->create([
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
        $user = factory(User::class)->create([
            'name' => 'Test User',
        ]);

        $body = factory(Body::class)->create();
        $body->giveToUser($user)->save();

        $user->moveTo($this->room)->save();

        $room = Room::with('contents')->first();

        $person = $room->people()->first();

        $this->assertEquals('Test User', $person->name);
    }

    /** @test */
    public function can_have_entities_in_it()
    {
        $potato = factory(Food::class)->create([
            'name' => 'Potato',
            'description' => 'A potato.',
        ]);

        $potato->moveToRoom($this->room);

        $potato->save();

        $room = Room::first();

        $this->assertEquals('A potato.', $room->contents->first()->getDescription());
    }
}
