<?php

namespace Tests\Unit\Dungeon\Commands;

use Dungeon\User;
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
    }

    /** @test */
    public function you_can_attack_people_in_the_same_room_as_you()
    {
        $this->user = $this->makeUser([
            'name' => 'Test User',
        ], 50);
        $this->enemy = $this->makeUser([
            'name' => 'Enemy',
        ], 100);
        $this->room = $this->makeRoom([
            'description' => 'A room. Maybe with a potato in it.',
        ]);
        $this->rock = $this->makeRock();

        $this->user->moveTo($this->room)->save();
        $this->enemy->moveTo($this->room)->save();

        $this->rock->giveToUser($this->user)->save();

        $command = new AttackCommand('attack enemy with rock', $this->user);
        $command->execute();

        $enemy = User::where('name', 'Enemy')->with('body')->first();

        $this->assertEquals(90, $enemy->body->getHealth());
    }

    /** @test */
    public function you_cant_attack_someone_in_another_room()
    {
        $this->user = $this->makeUser([
            'name' => 'Test User',
        ], 50);
        $this->enemy = $this->makeUser([
            'name' => 'Enemy',
        ], 100);
        $this->room = $this->makeRoom();
        $this->other_room = $this->makeRoom();
        $this->rock = $this->makeRock();

        $this->user->moveTo($this->room)->save();
        $this->enemy->moveTo($this->other_room)->save();

        $this->rock->giveToUser($this->user)->save();

        $command = new AttackCommand('attack enemy with rock', $this->user);
        $command->execute();

        $enemy = User::where('name', 'Enemy')->first();

        $this->assertEquals(100, $enemy->body->getHealth());
    }

    /** @test */
    public function users_cant_be_attacked_if_can_be_attacked_is_false()
    {
        $this->user = $this->makeUser([
            'name' => 'Test User',
        ], 50);
        $this->enemy = $this->makeUser([
            'name' => 'Enemy',
            'can_be_attacked' => false,
        ], 100);
        $this->room = $this->makeRoom();
        $this->rock = $this->makeRock();

        $this->user->moveTo($this->room)->save();
        $this->enemy->moveTo($this->room)->save();
        $this->rock->giveToUser($this->user)->save();

        $command = new AttackCommand('attack enemy with rock', $this->user);
        $command->execute();

        $enemy = User::where('name', 'Enemy')->with('body')->first();

        $this->assertEquals(100, $enemy->body->getHealth());
    }

    /** @test */
    public function users_cant_be_attacked_if_they_are_dead()
    {
        $this->user = $this->makeUser([
            'name' => 'Test User',
        ], 50);
        $this->enemy = $this->makeUser([
            'name' => 'Enemy',
            'can_be_attacked' => false,
        ], 100);
        $this->enemy->body->kill();
        $this->room = $this->makeRoom();
        $this->rock = $this->makeRock();

        $this->user->moveTo($this->room)->save();
        $this->enemy->moveTo($this->room)->save();
        $this->rock->giveToUser($this->user)->save();

        $command = new AttackCommand('attack enemy with rock', $this->user);
        $command->execute();

        $enemy = User::where('name', 'Enemy')->with('body')->first();

        $this->assertFalse($command->success);
        $this->assertNull($enemy->body);
    }

    /** @test */
    public function users_can_only_be_attacked_once_per_turn()
    {
        $this->user = $this->makeUser([
            'name' => 'Test User',
        ], 50);
        $this->enemy = $this->makeUser([
            'name' => 'Enemy',
        ], 100);
        $this->room = $this->makeRoom();
        $this->rock = $this->makeRock();

        $this->user->moveTo($this->room)->save();
        $this->enemy->moveTo($this->room)->save();
        $this->rock->giveToUser($this->user)->save();

        $command = new AttackCommand('attack enemy with rock', $this->user);
        $command->execute();

        $enemy = User::where('name', 'Enemy')->with('body')->first();

        // First attack reduces health
        $this->assertEquals(90, $enemy->body->getHealth());

        $command = new AttackCommand('attack enemy with rock', $this->user);
        $command->execute();

        $enemy = User::where('name', 'Enemy')->with('body')->first();

        // Second attack does nothing
        $this->assertEquals(90, $enemy->body->getHealth());
    }
}
