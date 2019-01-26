<?php

namespace App\Dungeon\Commands;

class HiCommand extends Command
{
    public function run()
    {
        $this->setMessage('I\'m not a fucking chat bot.');
    }
}