<?php

use Illuminate\Database\Seeder;
use App\Entity;

class EntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Entity::class)->create([
            'name' => 'Rock',
            'description' => 'It\'s a rock. You could probably hit things with it.',
            'class' => App\Weapon::Class,
            'data' => [
                'damage_amount' => 10,
                'damage_type' => 'blunt',
            ],
        ]);

        factory(Entity::class)->create([
            'name' => 'Hat',
            'description' => 'It\'s a hat that you can wear.',
            'class' => App\Weapon::Class,
            'data' => [
                'cold_protection' => 10,
                'blunt_protection' => 10,
            ],
        ]);
    }
}
