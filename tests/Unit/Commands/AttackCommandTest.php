<?php

namespace Tests\Unit\Dungeon\Commands;

use App\User;
use Dungeon\Room;
use Tests\TestCase;
use Dungeon\Entities\People\Body;
use Dungeon\Commands\AttackCommand;
use Dungeon\DamageTypes\MeleeDamage;
use Dungeon\Entities\Weapons\Melee\MeleeWeapon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @covers \Dungeon\Commands\AttackCommand
 */
class AttackCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function setup()
    {
        parent::setup();

        $this->user = factory(User::class)->create([
            'name' => 'Test User',
        ]);

        $this->body = factory(Body::class)->create();
        $this->body
            ->setHealth(50)
            ->giveToUser($this->user)
            ->save();

        $this->enemy = factory(User::class)->create([
            'name' => 'Enemy',
        ]);

        $this->enemy_body = factory(Body::class)->create();
        $this->enemy_body
            ->setHealth(100)
            ->giveToUser($this->enemy)
            ->save();

        $this->room = factory(Room::class)->create([
            'description' => 'A room. Maybe with a potato in it.',
        ]);

        $this->rock = factory(MeleeWeapon::class)->create([
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

        $enemy = User::where('name', 'Enemy')->with('body')->first();

        $this->assertEquals(50, $enemy->body->getHealth());
    }
}
