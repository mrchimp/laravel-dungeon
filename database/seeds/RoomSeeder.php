<?php

use Dungeon\Portal;
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
        $north_room = factory(Room::class)->create([
            'description' => 'You are in the start room. There is not a lot here.',
        ]);

        $south_room = factory(Room::class)->create([
            'description' => 'You are in the second room. You feel a profound sense of achievement for having made it here.',
        ]);

        $east_room = factory(Room::class)->create([
            'description' => 'Oh my god another room. Does this maze ever end? Yes it does. This is the last room.',
        ]);

        $north_south_portal = factory(Portal::class)->create();

        $north_room->setSouthExit(
            $south_room,
            [
                'description' => 'A wooden door.',
                'portal_id' => $north_south_portal->id
            ]
        );

        $south_east_portal = factory(Portal::class)->create([
            'locked' => true,
            'code' => 1234,
        ]);

        $south_room->setEastExit(
            $east_room,
            [
                'description' => 'A sturdy door with a code lock',
                'portal_id' => $south_east_portal->id,
            ]
        );
    }
}
