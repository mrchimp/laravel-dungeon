<?php

namespace Dungeon\Traits;

use Dungeon\Room;

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
}
