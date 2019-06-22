<?php

namespace Tests\Unit\Dungeon;

use Dungeon\Entities\Food\Food;
use Dungeon\Entities\People\Body;
use Dungeon\Entity;
use Dungeon\EntityFinder;
use Dungeon\Room;
use Dungeon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @covers \Dungeon\EntityFinder
 */
class EntityFinderTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    protected $user;

    protected $room;

    protected $potato;

    public function setup()
    {
        parent::setup();

        $this->user = factory(User::class)->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('fakepassword'),
        ]);

        $this->body = factory(Body::class)->create([
            'name' => 'Test Body',
        ]);

        $this->body->giveToUser($this->user)->save();
        $this->user->load('body');

        $this->room = factory(Room::class)->create([
            'description' => 'A room. Maybe with a potato in it.',
        ]);

        $this->potato = factory(Food::class)->create([
            'name' => 'A Potato',
            'description' => 'A potato.',
            'data' => [],
        ]);

        $this->box = factory(Entity::class)->create([
            'name' => 'Box of frogs',
            'description' => 'You can put things in it.',
            'class' => Entity::class,
            'data' => [],
        ]);

        $this->user->moveTo($this->room)->save();

        $this->finder = new EntityFinder($this->user);
    }

    /** @test */
    public function it_finds_entities_in_the_users_inventory()
    {
        $this->potato->giveToUser($this->user)->save();

        $entity = $this->finder->find('potato', $this->user);

        $this->assertEquals($this->potato->id, $entity->id);
    }

    /** @test */
    public function it_finds_entities_in_the_current_room()
    {
        $this->potato->moveToRoom($this->room)->save();

        $entity = $this->finder->find('potato', $this->user);

        $this->assertEquals($this->potato->id, $entity->id);
    }

    /** @test */
    public function it_find_entities_in_containers_that_are_in_the_current_room()
    {
        $this->box->moveToRoom($this->room)->save();
        $this->potato->moveToContainer($this->box)->save();
        $this->user->moveTo($this->room)->save();

        $entity = $this->finder->find('potato', $this->user);

        $this->assertEquals($this->potato->id, $entity->id);
    }

    /** @test */
    public function it_finds_users_in_the_same_room()
    {
        $other_player = factory(User::class)->create([
            'name' => 'Other Player',
        ]);

        $other_body = factory(Body::class)->create();
        $other_body->giveToUser($other_player)->save();
        $other_player->load('body');

        $this->user->moveTo($this->room)->save();
        $other_player->moveTo($this->room)->save();

        $entity = $this->finder->find('other player', $this->user);

        $this->assertEquals($other_player->body->id, $entity->id);

        $entity = $this->finder->find('other', $this->user);

        $this->assertEquals($other_player->body->id, $entity->id);
    }
}
