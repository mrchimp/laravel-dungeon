<?php

namespace Tests\Unit;

use Dungeon\Entity;
use Dungeon\EntityFinder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EntityFinderTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function it_finds_entities_in_the_users_inventory()
    {
        $user = $this->makeUser();

        $potato = $this->makePotato()->giveToUser($user);
        $potato->save();

        $finder = new EntityFinder($user);

        $entity = $finder->find('potato', $user);

        $this->assertEquals($potato->id, $entity->id);
    }

    /** @test */
    public function the_word_the_is_stripped_from_the_start_of_the_query()
    {
        $user = $this->makeUser();

        $potato = $this->makePotato()->giveToUser($user);
        $potato->save();

        $finder = new EntityFinder($user);

        $entity = $finder->find('the potato', $user);

        $this->assertEquals($potato->id, $entity->id);
    }

    /** @test */
    public function it_finds_entities_in_the_current_room()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);
        $potato = $this->makePotato();
        $potato->moveToRoom($room)->save();

        $finder = new EntityFinder($user);

        $entity = $finder->find('potato', $user);

        $this->assertEquals($potato->id, $entity->id);
    }

    /** @test */
    public function it_find_entities_in_containers_that_are_in_the_current_room()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);

        $box = $this->makeBox()->moveToRoom($room);
        $box->save();

        $potato = $this->makePotato();
        $potato->moveToContainer($box)->save();

        $finder = new EntityFinder($user);

        $entity = $finder->find('potato', $user);

        $this->assertEquals($potato->id, $entity->id);
    }

    /** @test */
    public function it_finds_users_in_the_same_room()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);
        $other_player = $this->makeUser([
            'name' => 'Other Player',
        ], 100, $room);

        $finder = new EntityFinder($user);

        $entity = $finder->find('other player', $user);

        $this->assertEquals($other_player->body->id, $entity->id);

        $entity = $finder->find('other', $user);

        $this->assertEquals($other_player->body->id, $entity->id);
    }

    /** @test */
    public function can_find_weapons()
    {
        $rocket = factory(Entity::class)->create();

        $user = $this->makeUser([], 10);
        $rock = $this->makeRock();

        $rocket->giveToUser($user)->save();
        $rock->giveToUser($user)->save();

        $finder = new EntityFinder($user);

        $entity = $finder->findWeaponInInventory('rock', $user);

        $this->assertEquals($rock->id, $entity->id);
    }
}
