<?php

use Dungeon\Entities\Apparel\Apparel;
use Dungeon\Entities\Food\Food;
use Dungeon\Entities\Weapons\Weapon;
use Dungeon\Room;
use Illuminate\Database\Seeder;

class EntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $room = Room::find(1);

        $rock = Weapon::create([
            'name' => 'Rock',
            'description' => 'You could probably hit things with it.',
            'class' => Weapon::class,
            'serialized_data' => [
                'damage_amount' => 10,
                'damage_type' => 'blunt',
            ],
        ]);
        $rock->moveToRoom($room);
        $rock->save();

        $hat = Apparel::create([
            'name' => 'Hat',
            'description' => 'Basic headwear.',
            'class' => Weapon::class,
            'serialized_data' => [
                'cold_protection' => 10,
                'blunt_protection' => 10,
            ],
        ]);
        $hat->moveToRoom($room);
        $hat->save();

        $potato = Food::create([
            'name' => 'Potato',
            'description' => 'A potato.',
            'class' => Food::class,
        ]);
        $potato->setHealing(10);
        $potato->moveToRoom($room);
        $potato->save();
    }
}
