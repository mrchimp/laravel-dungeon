<?php

namespace Tests\Unit\Dungeon\Commands;

use App\Dungeon\Commands\LookCommand;
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
    }

    /** @test */
    public function gets_a_response_if_not_in_a_room()
    {
        $command = new LookCommand($this->user);

        $response = $command->execute('look');

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

        $response = $command->execute('look');

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

        $response = $command->execute('look');

        $this->assertStringContainsString('Exits:', $response);
        $this->assertStringContainsString('This is the north room.', $response);
        $this->assertStringContainsString('A wooden door.', $response);
    }

    /** @test */
    public function you_can_see_other_people_if_they_are_in_the_same_room()
    {
        $this->user->moveTo($this->north_room);
        $this->user->save();

        $this->player_2->moveTo($this->north_room);
        $this->player_2->save();

        $command = new LookCommand($this->user);

        $response = $command->execute('look');

        $this->assertStringContainsString('Player 2', $response);
    }

    /** @test */
    public function you_can_see_npcs_if_they_are_in_the_same_room()
    {
        $this->user->moveTo($this->north_room);
        $this->user->save();

        $this->npc->moveTo($this->north_room);
        $this->npc->save();

        $command = new LookCommand($this->user);

        $response = $command->execute('look');

        $this->assertStringContainsString('Test NPC', $response);
    }
}