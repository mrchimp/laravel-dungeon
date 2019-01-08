<?php

use App\Dungeon\Apparel\Apparel;
use App\Dungeon\Entities\Food\Food;
use App\Dungeon\Entities\Weapon;
use App\Entity;
use App\Room;
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
            'class' => App\Weapon::class,
            'data' => [
                'damage_amount' => 10,
                'damage_type' => 'blunt',
            ],
        ]);
        $rock->moveToRoom($room);
        $rock->save();

        $hat = Apparel::create([
            'name' => 'Hat',
            'description' => 'Basic headwear.',
            'class' => App\Weapon::class,
            'data' => [
                'cold_protection' => 10,
                'blunt_protection' => 10,
            ],
        ]);
        $hat->moveToRoom($room);
        $hat->save();

        $potato = Food::create([
            'name' => 'Potato',
            'description' => 'A potato.',
            'class' => App\Food::class,
        ]);
        $potato->setHealing(10);
        $potato->moveToRoom($room);
        $potato->save();
    }
}
