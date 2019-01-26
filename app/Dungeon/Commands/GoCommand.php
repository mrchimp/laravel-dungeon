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
            return $this->fail('Go where?');
        }

        $direction = $this->input_array[1];

        if (!in_array($direction, $this->directions)) {
            return $this->fail('I don\'t know which way that is.');
        }

        $exit = $direction . 'Exit';
        $destination = $this->user->room->$exit;

        if (!$destination) {
            return $this->fail('I can\'t go that way.');
        }

        $this->user->moveTo($destination);
        $this->user->save();

        $this->setMessage('You go ' . $direction . '.');
    }
}