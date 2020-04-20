<?php

namespace Tests\Unit\Commands;

use Dungeon\Collections\EntityCollection;
use Dungeon\Commands\LookCommand;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LookCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function matches_look_syntax()
    {
        $user = $this->makeUser();

        $command = new LookCommand('look', $user);

        $matches = $command->matches();

        $this->assertTrue($matches);

        // 1, not 0 as matches[0] is the whole input
        $this->assertCount(1, $command->matchesArray());
    }

    /** @test */
    public function matches_look_at_object_syntax()
    {
        $user = $this->makeUser();

        $command = new LookCommand('look at potato', $user);

        $matches = $command->matches();

        $this->assertTrue($matches);
        $this->assertEquals('potato', $command->inputPart('target'));
    }

    /** @test */
    public function matches_look_at_object_syntax_with_spaces()
    {
        $user = $this->makeUser();

        $command = new LookCommand('look at hot potato', $user);

        $matches = $command->matches();

        $this->assertTrue($matches);
        $this->assertEquals('hot potato', $command->inputPart('target'));
    }

    /** @test */
    public function gets_a_response_if_not_in_a_room()
    {
        $user = $this->makeUser();

        $command = new LookCommand('look', $user);
        $command->execute();

        $response = $command->getMessage();

        $this->assertEquals(
            'You float in an endless void.',
            $response
        );
    }

    /** @test */
    public function gets_current_room_description_if_logged_in()
    {
        $north_room = $this->makeRoom([
            'description' => 'This is the north room.',
        ]);

        $user = $this->makeUser([], 100, $north_room);

        $command = new LookCommand('look', $user);
        $command->execute();

        $this->assertEquals(
            'This is the north room.',
            $command->getMessage()
        );
    }

    /** @test */
    public function gets_exits()
    {
        $north_room = $this->makeRoom();
        $south_room = $this->makeRoom();

        $user = $this->makeUser([], 100, $north_room);

        $north_room->setSouthExit($south_room);

        $command = new LookCommand('look', $user);

        $command->execute();
        $exits = $command->getOutputItem('exits');

        $this->assertIsCollection($exits);
        $this->assertCount(4, $exits);
        $this->assertEquals('A way out', $exits->get('south')->name);
        $this->assertEquals('Looks like you can go that way.', $exits->get('south')->description);
        $this->assertNull($exits->get('north'));
        $this->assertNull($exits->get('east'));
        $this->assertNull($exits->get('west'));
    }

    /** @test */
    public function you_can_see_other_people_if_they_are_in_the_same_room()
    {
        $north_room = $this->makeRoom();
        $south_room = $this->makeRoom();

        $user = $this->makeUser([], 100, $north_room);
        $this->makeUser([
            'name' => 'Player 2',
        ], 100, $north_room);

        $north_room->setSouthExit($south_room);

        $command = new LookCommand('look', $user);
        $command->execute();

        $players = $command->getOutputItem('players');

        $this->assertEquals(EntityCollection::class, get_class($players));
        $this->assertCount(1, $players);
        $this->assertEquals('Player 2', $players->first()->getName());
    }

    /** @test */
    public function you_can_see_npcs_if_they_are_in_the_same_room()
    {
        $north_room = $this->makeRoom();
        $user = $this->makeUser([], 100, $north_room);
        $this->makeNPC([
            'name' => 'Test NPC',
        ], 100, $north_room);

        $command = new LookCommand('look', $user);
        $command->execute();

        $npcs = $command->getOutputItem('npcs');

        $this->assertEquals(EntityCollection::class, get_class($npcs));
        $this->assertCount(1, $npcs);
        $this->assertEquals('Test NPC', $npcs->first()->getName());
    }

    /** @test */
    public function you_can_see_items_that_are_in_the_room()
    {
        $north_room = $this->makeRoom();
        $user = $this->makeUser([], 100, $north_room);
        $potato = $this->makePotato([
            'name' => 'Potato',
            'description' => 'You can eat it',
        ])->moveToRoom($north_room);
        $potato->save();

        $command = new LookCommand('look', $user);
        $command->execute();

        $items = $command->getOutputItem('items');

        $this->assertEquals(EntityCollection::class, get_class($items));
        $this->assertCount(1, $items);
        $this->assertEquals('Potato', $items->first()->getName());
    }

    /** @test */
    public function you_can_see_the_description_of_a_specific_entity()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);
        $this->makePotato([
            'name' => 'Potato',
            'description' => 'You can eat it',
        ])->moveToRoom($room)->save();

        $command = new LookCommand('look at potato', $user);
        $command->matches();
        $command->execute();

        $this->assertEquals('You can eat it', $command->getMessage());
    }
}
