<?php

use Dungeon\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $north_room = Room::create([
            'description' => 'You are in the start room. There is not a lot here.',
        ]);

        $south_room = Room::create([
            'description' => 'You are in the second room. You feel a profound sense of achievement for having made it here.',
        ]);

        $east_room = Room::create([
            'description' => 'Oh my god another room. Does this maze ever end? Yes it does. This is the last room.',
        ]);

        $north_room->setSouthExit($south_room, [
            'description' => 'A wooden door.',
        ]);

        $south_room->setEastExit($east_room, [
            'description' => 'A sturdy door with a code lock',
            'locked' => true,
            'code' => 1234,
        ]);
    }
}
