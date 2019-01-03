<?php

use App\Room;
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
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('secretpassword'),
        ]);

        $room = Room::first();

        $user->moveToRoom($room);
        $user->save();
    }
}
