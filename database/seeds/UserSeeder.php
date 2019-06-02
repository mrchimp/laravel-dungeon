<?php

use App\User;
use Dungeon\Room;
use Illuminate\Database\Seeder;
use Dungeon\Entities\People\Body;

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
            'name' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt('secretpassword'),
        ]);

        $player_1_body = Body::create([
            'name' => 'testuser',
            'description' => 'Player 1\'s body',
        ]);
        $player_1_body->giveToUser($player_1)->save();

        $player_1->moveTo($room)->save();


        $player_2 = User::create([
            'name' => 'player2',
            'email' => 'test2@example.com',
            'password' => bcrypt('secretpassword'),
        ]);

        $player_2_body = Body::create([
            'name' => 'player2',
            'description' => 'Player 2\'s body',
        ]);
        $player_2_body->giveToUser($player_2)->save();

        $player_2->moveTo($room)->save();
    }
}
