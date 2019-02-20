<?php

namespace Tests\Unit\Dungeon\Commands;

use Dungeon\Commands\EatCommand;
use Dungeon\Entities\Food\Food;
use App\Entity;
use App\Room;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EatCommandTest extends TestCase
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
        $this->user->setHealth(27)->save();

        $this->room = Room::create([
            'description' => 'A room. Maybe with a potato in it.',
        ]);

        $this->potato = Food::create([
            'name' => 'Potato',
            'description' => 'A potato.',
            'data' => [],
        ]);

        $this->potato->giveToUser($this->user);
        $this->potato->setHealing(37);
        $this->potato->save();

        $this->user->moveTo($this->room);
        $this->user->save();
    }

    /** @test */
    public function you_cant_eat_things_you_cant_find()
    {
        $command = new EatCommand('eat dodo', $this->user);
        $command->execute();

        $response = $command->getMessage();

        $this->assertStringContainsString('Could not find dodo.', $response);
    }

    /** @test */
    public function eating_things_will_affect_your_health()
    {
        $command = new EatCommand('eat potato', $this->user);
        $command->execute();

        $response = $command->getMessage();

        $this->assertEquals(64, $this->user->getHealth());
    }

    /** @test */
    public function you_cant_eat_things_that_arent_food()
    {
        $rock = Entity::create([
            'name' => 'Rock',
            'description' => 'An inedible object.',
            'data' => [],
        ]);

        $rock->giveToUser($this->user);
        $rock->save();

        $command = new EatCommand('eat rock', $this->user);
        $command->execute();

        $response = $command->getMessage();

        $this->assertEquals('You can\'t eat that.', $response);
    }

    /** @test */
    public function you_cant_have_your_cake_and_eat_it()
    {
        $command = new EatCommand('eat potato', $this->user);
        $command->execute();

        $response = $command->getMessage();

        $this->user->load('inventory');

        $this->assertEmpty($this->user->inventory);
    }
}