<?php

namespace App\Dungeon\Commands;

class GoCommand extends Command
{
    protected $directions = [
        'north',
        'south',
        'west',
        'east',
    ];

    public function run()
    {
        if (count($this->input_array) < 2) {
            $this->setMessage('Go where?');
            return;
        }

        $direction = $this->input_array[1];

        if (!in_array($direction, $this->directions)) {
            $this->setMessage('I don\'t know which way that is.');
            return;
        }

        $exit = $direction . 'Exit';
        $destination = $this->user->room->$exit;

        if (!$destination) {
            $this->setMessage('I can\'t go that way.');
            return;
        }

        $this->user->moveTo($destination);
        $this->user->save();

        $this->setMessage('You go ' . $direction . '.');
    }
}