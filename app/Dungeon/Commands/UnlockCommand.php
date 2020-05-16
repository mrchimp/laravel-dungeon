<?php

namespace Dungeon\Commands;

use Dungeon\Actions\Locks\Unlock;
use Dungeon\Direction;
use Dungeon\Exceptions\ActionFailedException;
use Dungeon\Exceptions\InvalidDirectionException;
use Dungeon\Exceptions\NoKeyAvailableException;
use Dungeon\Exceptions\PortalUnlockedException;

class UnlockCommand extends Command
{
    /**
     * Patterns that this command handles
     */
    public function patterns(): array
    {
        return [
            '/^unlock (?<direction>.*) door$/',
        ];
    }

    /**
     * Run the command
     */
    protected function run(): self
    {
        $direction = $this->inputPart('direction');

        try {
            Unlock::do($this->user, $direction);
        } catch (InvalidDirectionException $e) {
            return $this->fail('Which door? North, South, East or West?');
        } catch (PortalUnlockedException $e) {
            return $this->fail('The door is already unlocked.');
        } catch (NoKeyAvailableException $e) {
            return $this->fail('You don\'t have a way to unlock that door.');
        } catch (ActionFailedException $e) {
            return $this->fail('You can\'t unlock the door.');
        }


        // if (!Direction::isValid($direction)) {
        //     return $this->fail('Which door? North, South, East or West?');
        // }

        // $room = $this->user->body->room;
        // $portal = $room->{$direction . '_portal'};

        // if (!$portal) {
        //     return $this->fail('There is nothing to unlock in that direction.');
        // }

        // if (!$portal->isLocked()) {
        //     return $this->fail('The door is already unlocked.');
        // }

        // $key = $portal->whichKeyFits($this->user->getInventory());

        // if (!$key) {
        //     return $this->fail('You don\'t have a way to unlock that door.');
        // }

        // $result = $portal->unlockWithKey($key);

        // if ($result) {
        //     $this->setMessage('You unlock the door.');
        // } else {
        //     $this->setMessage('You fail to unlock the door.');
        // }

        $this->setMessage('You unlock the door.');

        return $this;
    }
}
