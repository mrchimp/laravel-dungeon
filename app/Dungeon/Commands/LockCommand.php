<?php

namespace Dungeon\Commands;

use Dungeon\Direction;

class LockCommand extends Command
{
    /**
     * Patterns that this command handles
     */
    public function patterns(): array
    {
        return [
            '/^lock (?<direction>.*) door with (?<access_type>.*) (?<access_name>.*)/',
            '/^lock (?<direction>.*) door with (?<access_type>.*)/',
            '/^lock (?<direction>.*) door$/',
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
            return $this->fail('Doors can only be locked with a code or a key.');
        }

        if ($access_type === 'code' && !$code) {
            return $this->fail('you need to provide a code.');
        }

        $room = $this->user->body->room;
        $portal = $room->{$direction . '_portal'};

        if ($portal->isLocked()) {
            return $this->fail('The door is already locked.');
        }

        if ($access_type === 'code') {
            if (!$portal->code) {
                return $this->fail('You can\'t lock that door with a code.');
            }

            $result = $portal->lockWithCode($code);
        } elseif ($access_type === 'key') {
            $key = $portal->whichKeyFits($this->user->getInventory());

            if (!$key) {
                return $this->fail('Nothing fits!');
            }

            $result = $portal->lockWithKey($key);
        }

        if ($result) {
            $this->setMessage('You lock the door.');
        } else {
            $this->setMessage('You can\'t lock the door.');
        }

        return $this;
    }
}
