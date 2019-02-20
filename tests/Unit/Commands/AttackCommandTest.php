<?php

namespace Tests\Unit\Dungeon\Commands;

use App\Room;
use App\User;
use Tests\TestCase;
use Dungeon\Commands\AttackCommand;
use Dungeon\Entities\Weapons\Melee\MeleeWeapon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Dungeon\DamageTypes\MeleeDamage;

class AttackCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function setup()
    {
        parent::setup();

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('fakepassword'),
        ]);
        $this->user->setHealth(50)->save();

        $this->enemy = User::create([
            'name' => 'Enemy',
            'email' => 'test2@example.com',
            'password' => bcrypt('fakepassword'),
        ]);
        $this->user->setHealth(50)->save();

        $this->room = Room::create([
            'description' => 'A room. Maybe with a potato in it.',
        ]);

        $this->rock = MeleeWeapon::create([
            'name' => 'Rock',
            'description' => 'You can hit people with it.',
            'damage_types' => [
                MeleeDamage::class => 50,
            ],
        ]);
    }

    /** @test */
    public function you_can_attack_people_in_the_same_room_as_you()
    {
        $this->user->moveTo($this->room)->save();
        $this->enemy->moveTo($this->room)->save();
        $this->rock->giveToUser($this->user)->save();

        $command = new AttackCommand('attack enemy with rock', $this->user);
        $command->execute();

        $enemy = User::where('name', 'Enemy')->first();

        $this->assertEquals(50, $enemy->getHealth());
    }
}
