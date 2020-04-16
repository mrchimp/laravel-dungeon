<?php

namespace Dungeon\Commands;

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
            '/^unlock (?<direction>.*) door with (?<access_type>.*) (?<access_name>.*)/'
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

        if (!in_array($access_type, ['code'])) {
            return $this->fail('Doors can only be unlocked with a code.');
        }

        $room = $this->user->body->room;
        $portal = $room->{$direction . '_portal'};

        if (!$portal->isLocked()) {
            return $this->fail('The door is already unlocked.');
        }

        $result = $portal->unlockWithCode($code);

        if ($result) {
            $this->appendMessage('You unlock the door. ');
        } else {
            $this->appendMessage('You fail to unlock the door. ');
        }
    }
}
