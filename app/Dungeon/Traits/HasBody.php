<?php

namespace Dungeon\Traits;

use Dungeon\Room;
use Illuminate\Support\Collection;

trait HasBody
{
    public function getBody()
    {
        if ($this->hasBody()) {
            return $this->body;
        }

        return null;
    }

    public function hasBody()
    {
        return !!$this->body;
    }

    public function moveTo(Room $room)
    {
        if (!$this->hasBody()) {
            throw new \Exception('Person does not have a body.');
        }

        return $this->body->moveToRoom($room);
    }

    public function getInventory(bool $refresh = false): Collection
    {
        if (!$this->body) {
            return collect([]);
        }

        if ($refresh) {
            $this->body->load('inventory');
        }

        return $this->body->inventory;
    }
}
