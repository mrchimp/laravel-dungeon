<?php

namespace Dungeon\Commands;

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
        $direction = $this->inputPart('direction');

        if (!$direction) {
            return $this->fail('Go where?');
        }

        if (!in_array($direction, $this->directions)) {
            return $this->fail('I don\'t know which way that is.');
        }

        $exit = $direction . 'Exit';
        $destination = $this->user->getRoom()->$exit;

        if (!$destination) {
            return $this->fail('I can\'t go that way.');
        }

        $this->user
            ->moveTo($destination)
            ->save();

        $this->setMessage('You go ' . $direction . '.');
    }
}
