<?php

namespace Tests\Unit\Dungeon\Commands;

use Dungeon\Commands\InventoryCommand;
use Dungeon\Entities\Food\Food;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class InventoryCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function you_can_see_what_is_in_your_inventory()
    {
        $room = $this->makeRoom();
        $user = $this->makeUser([], 100, $room);

        $potato = $this->makePotato()->giveToUser($user);
        $potato->save();

        $banana = factory(Food::class)->create([
            'name' => 'Banana',
            'description' => 'A banana.',
        ]);

        $banana->moveToRoom($room);
        $banana->save();

        $command = new InventoryCommand('inventory', $user);
        $command->execute();

        $response = $command->getMessage();

        $this->assertStringContainsString('Potato', $response);
        $this->assertStringNotContainsString('Banana', $response);
    }
}
