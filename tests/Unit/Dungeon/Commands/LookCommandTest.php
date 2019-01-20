<?php

namespace Tests\Unit\Dungeon\Commands;

use App\Dungeon\Commands\LookCommand;
use App\Dungeon\Entities\Food\Food;
use App\NPC;
use App\Room;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LookCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    protected $user;

    public function setup()
    {
        parent::setup();

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('fakepassword'),
        ]);

        $this->player_2 = User::create([
            'name' => 'Player 2',
            'email' => 'player2@example.com',
            'password' => bcrypt('fakepassword'),
        ]);

        $this->npc = NPC::create([
            'name' => 'Test NPC',
            'description' => 'An NPC for testing',
        ]);

        $this->north_room = Room::create([
            'description' => 'This is the north room.',
        ]);

        $this->south_room = Room::create([
            'description' => 'This is the south room.',
        ]);

        $this->potato = new Food([
            'name' => 'Potato',
            'description' => 'You can eat it',
        ]);
    }

    /** @test */
    public function gets_a_response_if_not_in_a_room()
    {
        $command = new LookCommand($this->user);

        $command->execute('look');
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

        $command = new LookCommand($this->user);

        $command->execute('look');
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

        $command = new LookCommand($this->user);

        $command->execute('look');
        $exits = $command->getOutputItem('exits');

        $this->assertIsCollection($exits);
        $this->assertCount(1, $exits);
    }

    /** @test */
    public function you_can_see_other_people_if_they_are_in_the_same_room()
    {
        $this->user->moveTo($this->north_room)->save();
        $this->player_2->moveTo($this->north_room)->save();

        $command = new LookCommand($this->user);

        $command->execute('look');
        $players = $command->getOutputItem('players');

        $this->assertIsCollection($players);
        $this->assertCount(1, $players);
        $this->assertEquals('Player 2', $players->first()->getName());
    }

    /** @test */
    public function you_can_see_npcs_if_they_are_in_the_same_room()
    {
        $this->user->moveTo($this->north_room)->save();
        $this->npc->moveTo($this->north_room)->save();

        $command = new LookCommand($this->user);

        $command->execute('look');
        $npcs = $command->getOutputItem('npcs');

        $this->assertIsCollection($npcs);
        $this->assertCount(1, $npcs);
        $this->assertEquals('Test NPC', $npcs->first()->getName());
    }

    /** @test */
    public function you_can_see_items_that_are_in_the_room()
    {
        $this->user->moveTo($this->north_room)->save();
        $this->potato->moveToRoom($this->north_room)->save();

        $command = new LookCommand($this->user);

        $command->execute('look');

        $items = $command->getOutputItem('items');

        $this->assertIsEntityCollection($items);
        $this->assertCount(1, $items);
        $this->assertEquals('Potato', $items->first()->getName());
    }
}
