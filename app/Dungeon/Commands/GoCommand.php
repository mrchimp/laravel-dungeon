<?php

namespace Dungeon\Commands;

use Dungeon\Direction;

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
        $direction = Direction::sanitize($this->inputPart('direction'));

        if (!$direction) {
            return $this->fail('Go where?');
        }

        $destination = $this->user->getRoom()->{$direction . 'Exit'};

        if (!$destination) {
            return $this->fail('I can\'t go that way.');
        }

        $portal = $this->user->getRoom()->{$direction . '_portal'};

        if ($portal && $portal->isLocked()) {
            return $this->fail('The door is locked.');
        }

        $this->user
            ->moveTo($destination)
            ->save();

        $this->setMessage('You go ' . $direction . '. ' . $destination->getDescription());

        return $this;
    }
}
