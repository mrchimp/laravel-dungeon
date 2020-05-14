<?php

namespace Dungeon\Commands;

use Dungeon\Direction;

class UnlockCommand extends Command
{
    /**
     * Patterns that this command handles
     */
    public function patterns(): array
    {
        return [
            '/^unlock (?<direction>.*) door with (?<access_type>.*) (?<access_name>.*)$/',
            '/^unlock (?<direction>.*) door with (?<access_type>.*)$/',
            '/^unlock (?<direction>.*) door$/',
        ];
    }

    /**
     * Run the command
     */
    protected function run(): self
    {
        $direction = $this->inputPart('direction');
        $access_type = $this->inputPart('access_type');
        $code = $this->inputPart('access_name');

        if (!$access_type) {
            $access_type = 'key';
        }

        if (!Direction::isValid($direction)) {
            return $this->fail('Which door? North, South, East or West?');
        }

        if (!in_array($access_type, ['code', 'key'])) {
            return $this->fail('Doors can only be unlocked with a code or a key.');
        }

        $room = $this->user->body->room;
        $portal = $room->{$direction . '_portal'};

        if (!$portal) {
            return $this->fail('There is nothing to unlock in that direction.');
        }

        if (!$portal->isLocked()) {
            return $this->fail('The door is already unlocked.');
        }

        $key = $portal->whichKeyFits($this->user->getInventory());

        if (!$key) {
            return $this->fail('You don\'t have a way to unlock that door.');
        }

        $result = $portal->unlockWithKey($key);

        if ($result) {
            $this->setMessage('You unlock the door.');
        } else {
            $this->setMessage('You fail to unlock the door.');
        }

        return $this;
    }
}
