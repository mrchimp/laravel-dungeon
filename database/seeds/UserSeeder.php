<?php

use Dungeon\Room;
use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $room = Room::first();

        $player_1 = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('secretpassword'),
        ]);

        $player_1->moveTo($room);
        $player_1->save();

        $player_2 = User::create([
            'name' => 'Player 2',
            'email' => 'test2@example.com',
            'password' => bcrypt('secretpassword'),
        ]);

        $player_2->moveTo($room);
        $player_2->save();
    }
}
