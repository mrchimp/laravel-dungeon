<?php

namespace Dungeon\Commands;

use Dungeon\Actions\Locks\Lock;
use Dungeon\Exceptions\ActionFailedException;
use Dungeon\Exceptions\InvalidDirectionException;
use Dungeon\Exceptions\NoKeyAvailableException;
use Dungeon\Exceptions\PortalLockedException;

class LockCommand extends Command
{
    /**
     * Patterns that this command handles
     */
    public function patterns(): array
    {
        return [
            '/^lock (?<direction>.*) door$/',
        ];
    }

    /**
     * Run the command
     */
    protected function run(): self
    {
        $direction = $this->inputPart('direction');

        try {
            Lock::do($this->user, $direction);
        } catch (InvalidDirectionException $e) {
            return $this->fail('Which door? North, South, East or West?');
        } catch (PortalLockedException $e) {
            return $this->fail('The door is already locked.');
        } catch (NoKeyAvailableException $e) {
            return $this->fail('You don\'t have a way to lock that door.');
        } catch (ActionFailedException $e) {
            return $this->fail('You can\'t lock the door.');
        }

        $this->setMessage('You lock the door.');

        return $this;
    }
}
