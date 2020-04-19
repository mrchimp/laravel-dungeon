<?php

namespace Dungeon\Commands;

class HelpCommand extends Command
{
    protected function run(): self
    {
        $output = 'Available commands: drop, eat, equip, help, go, inspect, inventory, look, take';

        $this->setMessage($output);

        return $this;
    }
}
