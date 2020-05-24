<?php

namespace Tests\Unit\Commands;

use Dungeon\Commands\AttackCommand;
use Dungeon\Entities\People\Body;
use Dungeon\Events\AfterAttack;
use Dungeon\Events\BeforeAttack;
use Dungeon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AttackCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function you_can_attack_people_in_the_same_room_as_you()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 50, $room);
        $enemy = $this->makeEnemy([], 100, $room);

        $rock = $this->makeRock();
        $rock->giveToUser($user)->save();

        $command = new AttackCommand('attack enemy with rock', $user);
        $command->execute();

        $enemy = User::where('name', 'Enemy')->with('body')->first();

        $this->assertEquals(90, $enemy->body->getHealth());
    }

    /** @test */
    public function you_cant_attack_someone_in_another_room()
    {
        $room = $this->makeRoom();
        $other_room = $this->makeRoom();
        $user = $this->makeUser([], 50, $room);
        $enemy = $this->makeEnemy([], 100, $other_room);

        $rock = $this->makeRock();
        $rock->giveToUser($user)->save();

        $command = new AttackCommand('attack enemy with rock', $user);
        $command->execute();

        $enemy = User::where('name', 'Enemy')->first();

        $this->assertEquals(100, $enemy->body->getHealth());
    }

    /** @test */
    public function users_cant_be_attacked_if_can_be_attacked_is_false()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 50, $room);
        $enemy = $this->makeEnemy([
            'can_be_attacked' => false,
        ], 100, $room);

        $rock = $this->makeRock();
        $rock->giveToUser($user)->save();

        $command = new AttackCommand('attack enemy with rock', $user);
        $command->execute();

        $enemy = User::where('name', 'Enemy')->with('body')->first();

        $this->assertEquals(100, $enemy->body->getHealth());
    }

    /** @test */
    public function users_cant_be_attacked_if_they_are_dead()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 50, $room);
        $body = factory(Body::class)->create([
            'name' => 'Enemy',
            'description' => 'A dead body',
        ]);

        $body->moveToRoom($room)->save();

        $this->makeRock()->giveToUser($user)->save();

        $command = new AttackCommand('attack enemy with rock', $user);
        $command->execute();

        $this->assertFalse($command->success);
    }

    /** @test */
    public function users_can_only_be_attacked_once_per_turn()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 50, $room);
        $enemy = $this->makeEnemy([], 100, $room);
        $rock = $this->makeRock();

        $rock->giveToUser($user)->save();

        $command = new AttackCommand('attack enemy with rock', $user);
        $command->execute();

        $enemy = User::where('name', 'Enemy')->with('body')->first();

        // First attack reduces health
        $this->assertEquals(90, $enemy->body->getHealth());

        $command = new AttackCommand('attack enemy with rock', $user);
        $command->execute();

        $enemy = User::where('name', 'Enemy')->with('body')->first();

        // Second attack does nothing
        $this->assertEquals(90, $enemy->body->getHealth());
    }

    /** @test */
    public function dealing_enough_damage_kills_the_user()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 50, $room);
        $enemy = $this->makeEnemy([], 100, $room);
        $rock = $this->makeRock(200);

        $rock->giveToUser($user)->save();

        $command = new AttackCommand('attack enemy with rock', $user);
        $command->execute();

        $enemy = User::where('name', 'Enemy')->with('body')->first();

        $this->assertTrue($command->success);
        $this->assertTrue($enemy->isDead());
        $this->assertNull($enemy->body);
    }

    /** @test */
    public function beforeAttack_event_is_triggered_before_an_attack_happens()
    {
        $this->expectsEvents(BeforeAttacK::class);

        $room = $this->makeRoom();
        $user = $this->makeUser([], 50, $room);
        $this->makeEnemy([], 100, $room);
        $rock = $this->makeRock(100);

        $rock->giveToUser($user)->save();

        $command = new AttackCommand('attack enemy with rock', $user);
        $command->execute();
    }

    /** @test */
    public function afterAttack_event_is_triggered_after_an_attack_happens()
    {
        $this->expectsEvents(AfterAttack::class);

        $room = $this->makeRoom();
        $user = $this->makeUser([], 50, $room);
        $this->makeEnemy([], 100, $room);
        $this->makeRock(100)->giveToUser($user)->save();

        $command = new AttackCommand('attack enemy with rock', $user);
        $command->execute();
    }

    /** @test */
    public function you_cannot_attack_in_a_safe_room()
    {
        $room = $this->makeRoom([
            'is_safe_room' => true,
        ]);

        $user = $this->makeUser([], 100, $room);
        $this->makeEnemy([], 100, $room);
        $this->makeRock()->giveToUser($user)->save();

        $command = new AttackCommand('attack enemy with rock', $user);
        $command->execute();

        $this->assertEquals('You can\'t attack people in a safe room.', $command->getMessage());
        $this->assertFalse($command->success);
    }
}
