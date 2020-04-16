<?php

namespace Dungeon\Commands;

use Dungeon\Direction;

class GoCommand extends Command
{
    /**
     * Allowed directions
     *
     * @var array
     */
    protected $directions = [
        'north',
        'south',
        'west',
        'east',
    ];

    /**
     * Patterns that this command handles
     *
     * @return array
     */
    public function patterns()
    {
        return [
            '/^go$/',
            '/^go (?<direction>.*)$/',
        ];
    }

    /**
     * Run the command
     *
     * @return null
     */
    protected function run()
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
    }
}
