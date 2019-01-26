<?php

namespace App\Dungeon\Commands;

class HelpCommand extends Command
{
    public function run()
    {
        $output = 'Available commands: drop, eat, equip, help, go, inspect, inventory, look, take';

        $this->setMessage($output);
    }
}
