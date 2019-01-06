<?php

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

        $rock = factory(Entity::class)->create([
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

        $hat = factory(Entity::class)->create([
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

        $potato = factory(Entity::class)->create([
            'name' => 'Potato',
            'description' => 'A potato.',
            'class' => App\Food::class,
        ]);
        $potato->moveToRoom($room);
        $potato->save();
    }
}
