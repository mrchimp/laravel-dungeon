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
            return 'Go where?';
        }

        $direction = $this->input_array[1];

        if (!in_array($direction, $this->directions)) {
            return 'I don\'t know which way that is.';
        }

        $exit = $direction . 'Exit';
        $destination = $this->user->room->$exit;

        if (!$destination) {
            return 'I can\'t go that way.';
        }

        $this->user->moveTo($destination);
        $this->user->save();

        $look_command = new LookCommand($this->user);

        return 'You go ' . $direction . '. ' . $look_command->run('look');
    }
}