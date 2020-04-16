<?php

namespace Dungeon\Commands;

use Dungeon\Contracts\KeyInterface;
use Dungeon\Direction;

class UnlockCommand extends Command
{
    /**
     * Patterns that this command handles
     *
     * @return array
     */
    public function patterns()
    {
        return [
            '/^unlock (?<direction>.*) door with (?<access_type>.*) (?<access_name>.*)$/',
            '/^unlock (?<direction>.*) door with (?<access_type>.*)$/',
            '/^unlock (?<direction>.*) door$/',
        ];
    }

    /**
     * Run the command
     *
     * @return null
     */
    protected function run()
    {
        $direction = $this->inputPart('direction');
        $access_type = $this->inputPart('access_type');
        $code = $this->inputPart('access_name');

        if (!Direction::isValid($direction)) {
            return $this->fail('Which door? North, South, East or West?');
        }

        $room = $this->user->body->room;
        $portal = $room->{$direction . '_portal'};

        if (!$portal->isLocked()) {
            return $this->fail('The door is already unlocked.');
        }

        if (!in_array($access_type, ['code', 'key'])) {
            return $this->fail('Doors can only be unlocked with a code or a key.');
        }

        if ($access_type === 'code') {
            if (!$code) {
                return $this->fail('You need to provide a code.');
            }

            if (!$portal->code) {
                return $this->fail('You can\'t open this door with a code');
            }

            $result = $portal->unlockWithCode($code);
        } else if ($access_type === 'key') {
            $key = $portal->whichKeyFits($this->user->getInventory());

            if (!$key) {
                return $this->fail('Nothing fits!');
            }

            $result = $portal->unlockWithKey($key);
        }


        if ($result) {
            $this->appendMessage('You unlock the door. ');
        } else {
            $this->appendMessage('You fail to unlock the door. ');
        }
    }

    /**
     * Unlock the door with a given key
     *
     * @param KeyInterface $key
     * @return void
     */
    protected function unlockWithKey(KeyInterface $key)
    {
    }
}
