<?php

use App\Room;
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
        Room::create([
            'description' => 'You are in the start room. There is not a lot here.',
        ]);
    }
}
