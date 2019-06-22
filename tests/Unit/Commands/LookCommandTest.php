<?php

namespace Tests\Unit\Dungeon\Commands;

use App\User;
use Dungeon\NPC;
use Dungeon\Room;
use Tests\TestCase;
use Dungeon\Entities\Food\Food;
use Dungeon\Commands\LookCommand;
use Dungeon\Entities\People\Body;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Dungeon\Collections\EntityCollection;

/**
 * @covers \Dungeon\Commands\LookCommand
 */
class LookCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    protected $user;

    public function setup()
    {
        parent::setup();

        $this->user = factory(User::class)->create([
            'name' => 'Test User',
        ]);

        $user_body = factory(Body::class)
            ->create()
            ->giveToUser($this->user);
        $user_body->save();

        $this->player_2 = factory(User::class)->create([
            'name' => 'Player 2',
        ]);

        $player_2_body = factory(Body::class)
            ->create()
            ->giveToUser($this->player_2);
        $player_2_body->save();

        $this->npc = factory(NPC::class)->create([
            'name' => 'Test NPC',
            'description' => 'An NPC for testing',
        ]);

        $npc_body = factory(Body::class)
            ->create()
            ->giveToNPC($this->npc);
        $npc_body->save();

        $this->npc->load('body');
        $this->npc->fresh();

        $this->north_room = factory(Room::class)->create([
            'description' => 'This is the north room.',
        ]);

        $this->south_room = factory(Room::class)->create([
            'description' => 'This is the south room.',
        ]);

        $this->potato = factory(Food::class)->create([
            'name' => 'Potato',
            'description' => 'You can eat it',
        ]);
    }

    /** @test */
    public function matches_look_syntax()
    {
        $command = new LookCommand('look', $this->user);

        $matches = $command->matches();

        $this->assertTrue($matches);

        // 1, not 0 as matches[0] is the whole input
        $this->assertCount(1, $command->matchesArray());
    }

    /** @test */
    public function matches_look_at_object_syntax()
    {
        $command = new LookCommand('look at potato', $this->user);

        $matches = $command->matches();

        $this->assertTrue($matches);
        $this->assertEquals('potato', $command->inputPart('target'));
    }

    /** @test */
    public function matches_look_at_object_syntax_with_spaces()
    {
        $command = new LookCommand('look at hot potato', $this->user);

        $matches = $command->matches();

        $this->assertTrue($matches);
        $this->assertEquals('hot potato', $command->inputPart('target'));
    }

    /** @test */
    public function gets_a_response_if_not_in_a_room()
    {
        $command = new LookCommand('look', $this->user);

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
        $this->user->moveTo($this->north_room);

        $command = new LookCommand('look', $this->user);

        $command->execute();
        $response = $command->getMessage();

        $this->assertEquals(
            'This is the north room.',
            $response
        );
    }

    /** @test */
    public function gets_exits()
    {
        $this->user->moveTo($this->north_room);
        $this->north_room->setSouthExit($this->south_room, [
            'description' => 'A wooden door.',
        ]);

        $command = new LookCommand('look', $this->user);

        $command->execute();
        $exits = $command->getOutputItem('exits');

        $this->assertIsCollection($exits);
        $this->assertCount(1, $exits);
    }

    /** @test */
    public function you_can_see_other_people_if_they_are_in_the_same_room()
    {
        $this->user->moveTo($this->north_room)->save();
        $this->player_2->moveTo($this->north_room)->save();

        $command = new LookCommand('look', $this->user);

        $command->execute();
        $players = $command->getOutputItem('players');

        $this->assertEquals(EntityCollection::class, get_class($players));
        $this->assertCount(1, $players);
        $this->assertEquals('Player 2', $players->first()->getName());
    }

    /** @test */
    public function you_can_see_npcs_if_they_are_in_the_same_room()
    {
        $this->user->moveTo($this->north_room)->save();
        $this->npc->moveTo($this->north_room)->save();

        $command = new LookCommand('look', $this->user);

        $command->execute();
        $npcs = $command->getOutputItem('npcs');

        $this->assertEquals(EntityCollection::class, get_class($npcs));
        $this->assertCount(1, $npcs);
        $this->assertEquals('Test NPC', $npcs->first()->getName());
    }

    /** @test */
    public function you_can_see_items_that_are_in_the_room()
    {
        $this->user->moveTo($this->north_room)->save();
        $this->potato->moveToRoom($this->north_room)->save();

        $command = new LookCommand('look', $this->user);

        $command->execute();

        $items = $command->getOutputItem('items');

        $this->assertEquals(EntityCollection::class, get_class($items));
        $this->assertCount(1, $items);
        $this->assertEquals('Potato', $items->first()->getName());
    }

    /** @test */
    public function you_can_see_the_description_of_a_specific_entity()
    {
        $this->user->moveTo($this->north_room)->save();
        $this->potato->moveToRoom($this->north_room)->save();

        $command = new LookCommand('look at potato', $this->user);
        $command->matches();
        $command->execute();

        $this->assertEquals('You can eat it', $command->getMessage());
    }
}
