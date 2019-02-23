<?php

namespace Tests\Unit\Dungeon\Commands;

use Dungeon\Commands\TakeCommand;
use Dungeon\Entities\Food\Food;
use Dungeon\Entity;
use Dungeon\Room;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TakeCommandTest extends TestCase
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

        $this->room = Room::create([
            'description' => 'A room. Maybe with a potato in it.',
        ]);

        $this->potato = Food::create([
            'name' => 'Potato',
            'description' => 'A potato.',
            'data' => [],
        ]);

        $this->box = Entity::create([
            'name' => 'Box',
            'description' => 'You can put things in it.',
            'class' => Entity::class,
            'data' => [],
        ]);

        $this->user->moveTo($this->room)->save();
    }

    /** @test */
    public function cant_take_things_you_already_have()
    {
        $this->potato->giveToUser($this->user)->save();

        $command = new TakeCommand('take potato', $this->user);
        $command->execute();

        $potato = Entity::find($this->potato->id);

        $this->assertEquals($this->user->id, $potato->owner_id);
    }

    /** @test */
    public function can_take_things_from_the_room_youre_in()
    {
        $this->potato->moveToRoom($this->room);
        $this->potato->save();

        $command = new TakeCommand('take potato', $this->user);
        $command->execute();

        $potato = Entity::find($this->potato->id);

        $this->assertEquals($this->user->id, $potato->owner_id);
    }

    /** @test */
    public function can_take_things_from_containers_in_the_room()
    {
        $this->potato->moveToContainer($this->box);
        $this->potato->save();

        $this->box->moveToRoom($this->room);
        $this->box->save();

        $command = new TakeCommand('take potato', $this->user);
        $command->execute();

        $potato = Entity::find($this->potato->id);

        $this->assertEquals($this->user->id, $potato->owner_id);
    }

    public function you_cant_take_things_that_dont_exist()
    {
        $command = new TakeCommand('take avocado', $this->user);
        $response = $command->execute();

        $this->assertEquals('Take what?', $response);
    }
}