<?php

namespace Dungeon\Commands;

class HiCommand extends Command
{
    protected function run(): self
    {
        $this->setMessage('I\'m not a fucking chat bot.');

        return $this;
    }
}
