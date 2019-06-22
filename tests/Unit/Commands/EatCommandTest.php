<?php

namespace Tests\Unit\Dungeon\Commands;

use App\User;
use Dungeon\Room;
use Dungeon\Entity;
use Tests\TestCase;
use Dungeon\Entities\Food\Food;
use Dungeon\Commands\EatCommand;
use Dungeon\Entities\People\Body;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @covers \Dungeon\Commands\EatCommand
 */
class EatCommandTest extends TestCase
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
            ->giveToUser($this->user)
            ->setHealth(27)
            ->save();

        $this->room = factory(Room::class)->create([
            'description' => 'A room. Maybe with a potato in it.',
        ]);

        $this->potato = factory(Food::class)->create([
            'name' => 'Potato',
            'description' => 'A potato.',
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
        $rock = factory(Entity::class)->create([
            'name' => 'Rock',
            'description' => 'An inedible object.',
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

        $command->getMessage();

        $this->user->load('inventory');

        $this->assertEmpty($this->user->getInventory());
    }
}
