<?php

namespace Tests\Unit\Dungeon\Commands;

use App\Dungeon\Commands\EatCommand;
use App\Dungeon\Entities\Food\Food;
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

        $this->command = new EatCommand($this->user);
    }

    /** @test */
    public function you_cant_eat_things_you_cant_find()
    {
        $response = $this->command->execute('eat dodo');

        $this->assertStringContainsString('Could not find dodo.', $response);
    }

    /** @test */
    public function eating_things_will_affect_your_health()
    {
        $response = $this->command->execute('eat potato');

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

        $response = $this->command->execute('eat rock');

        $this->assertEquals('You can\'t eat that.', $response);
    }

    /** @test */
    public function you_cant_have_your_cake_and_eat_it()
    {
        $response = $this->command->execute('eat potato');

        $this->user->load('inventory');

        $this->assertEmpty($this->user->inventory);
    }
}