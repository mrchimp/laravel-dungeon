<?php

namespace Dungeon\Commands;

use Dungeon\Actions\Users\Go;
use Dungeon\Exceptions\ExitUnavailableException;
use Dungeon\Exceptions\InvalidDirectionException;
use Dungeon\Exceptions\PortalLockedException;

class GoCommand extends Command
{
    /**
     * Allowed directions
     */
    protected array $directions = [
        'north',
        'south',
        'west',
        'east',
    ];

    /**
     * Patterns that this command handles
     */
    public function patterns(): array
    {
        return [
            '/^go$/',
            '/^go (?<direction>.*)$/',
        ];
    }

    /**
     * Run the command
     */
    protected function run(): self
    {
        try {
            $action = Go::do($this->user, $this->inputPart('direction'));
        } catch (InvalidDirectionException $e) {
            return $this->fail('Go where?');
        } catch (ExitUnavailableException $e) {
            return $this->fail('You can\'t go that way.');
        } catch (PortalLockedException $e) {
            return $this->fail('The door is locked.');
        }

        $this->setMessage('You go ' . $action->direction . '. ' . $action->destination->getDescription());

        return $this;
    }
}
