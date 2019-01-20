<?php

namespace App\Dungeon\Commands;

use App\Room;
use Auth;

class LookCommand extends Command
{
    protected $exits = [];

    protected $items = [];

    protected $players = [];

    protected $npcs = [];

    protected $description = '';

    public function run()
    {
        if (is_null($this->user->room)) {
            $this->setMessage($this->current_location->getDescription());
            return;
        }

        $this->setOutputItem('exits', $this->current_location->getExits());
        $this->setOutputItem('items', $this->current_location->getItems());
        $this->setOutputItem('players', $this->current_location->getPlayers());
        $this->setOutputItem('npcs', $this->current_location->getNpcs());
        $this->setOutputItem('inventory', $this->user->getInventory());

        $this->setMessage($this->current_location->getDescription());
    }
}
