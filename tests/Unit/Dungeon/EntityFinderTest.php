<?php

namespace Tests\Unit\Dungeon;

use App\Dungeon\Entities\Finder;
use App\Dungeon\Entities\Food\Food;
use App\Room;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EntityFinderClass extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    protected $user;

    protected $room;

    protected $potato;

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
        ]);

        $this->user->moveTo($this->room);
        $this->user->save();

        $this->finder = new Finder($this->user);
    }

    /** @test */
    public function it_finds_entities_in_the_users_inventory()
    {
        $this->potato->giveToUser($this->user);
        $this->potato->save();

        $entity = $this->finder->find('potato');

        $this->assertEquals($this->potato->id, $entity->id);
    }

    /** @test */
    public function it_finds_entities_in_the_current_room()
    {
        $this->potato->moveToRoom($this->room);
        $this->potato->save();

        $entity = $this->finder->find('potato');

        $this->assertEquals($this->potato->id, $entity->id);
    }
}