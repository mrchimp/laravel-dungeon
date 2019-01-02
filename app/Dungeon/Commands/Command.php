<?php

namespace App\Dungeon\Commands;

abstract class Command {
    abstract function run(string $input);
}