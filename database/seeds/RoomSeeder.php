<?php

use Dungeon\Entities\Locks\Key;
use Dungeon\Entity;
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
            'description' => 'Oh my god another room. Does this maze ever end? Yes it does. This is the last room. Or is it?',
        ]);

        $west_room = factory(Room::class)->create([
            'description' => 'Yet more rooms. This one has green wallpaper.',
        ]);

        $north_south_portal = Portal::create([
            'name' => 'Wooden door',
            'description' => 'A wooden door. It\'s unlocked.',
        ]);

        $north_room->setSouthExit(
            $south_room,
            [
                'portal_id' => $north_south_portal->id
            ]
        );

        $south_east_portal = Portal::createWithSerializable([
            'name' => 'Metal door',
            'description' => 'A sturdy door with a code lock. It\'s locked. Maybe you can guess the code.',
            'locked' => true,
            'code' => 1234,
        ]);

        $south_room->setEastExit(
            $east_room,
            [
                'portal_id' => $south_east_portal->id,
            ]
        );

        $south_west_portal = Portal::createWithSerializable([
            'locked' => true,
            'name' => 'Metal door',
            'description' => 'A metal door with a key hole in it.',
        ]);

        $west_room_key = Key::create([
            'name' => 'Metal key',
            'description' => 'A small metal key that looks like it would fit in a metal door. Because that is obviously how things work, right?',
        ]);
        $west_room_key->moveToRoom($south_room)->save();

        $south_west_portal->keys()->attach($west_room_key);

        $south_room->setWestExit(
            $west_room,
            [
                'portal_id' => $south_west_portal->id,
            ]
        );

        $statue = Entity::createWithSerializable([
            'name' => 'Statue',
            'description' => 'A monster of vaguely anthropoid outline, but with an octopus-like head whose face is a mass of feelers, a scaly, rubbery-looking body, prodigious claws on hind and fore feet, and long, narrow wings behind.',
            'can_be_taken' => false,
        ]);

        $statue->moveToRoom($north_room)->save();
    }
}
