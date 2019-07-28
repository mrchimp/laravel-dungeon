<?php

namespace Dungeon;

use Dungeon\Commands\AttackCommand;
use Dungeon\Commands\DropCommand;
use Dungeon\Commands\EatCommand;
use Dungeon\Commands\EquipCommand;
use Dungeon\Commands\GiveCommand;
use Dungeon\Commands\GoCommand;
use Dungeon\Commands\HelpCommand;
use Dungeon\Commands\HiCommand;
use Dungeon\Commands\InspectCommand;
use Dungeon\Commands\InventoryCommand;
use Dungeon\Commands\KillCommand;
use Dungeon\Commands\LookCommand;
use Dungeon\Commands\RespawnCommand;
use Dungeon\Commands\SayCommand;
use Dungeon\Commands\TakeCommand;
use Dungeon\Commands\WhisperCommand;
use Dungeon\Exceptions\UnknownCommandException;
use Log;

class CommandRunner
{
    protected static $commands = [
        AttackCommand::class,
        DropCommand::class,
        EatCommand::class,
        EquipCommand::class,
        GoCommand::class,
        HelpCommand::class,
        HiCommand::class,
        InspectCommand::class,
        InventoryCommand::class,
        KillCommand::class,
        LookCommand::class,
        TakeCommand::class,
        RespawnCommand::class,
        GiveCommand::class,
        SayCommand::class,
        WhisperCommand::class,
    ];

    /**
     * Take an input string and run the appropriate command
     *
     * @return void
     */
    public static function run($input, $user = null)
    {
        $chunks = explode(' ', $input);

        foreach (self::$commands as $command) {
            $command = new $command($input, $user);

            if ($command->matched) {
                break;
            }

            $command = null;
        }

        if (!$command) {
            Log::warning('Unknown command: ' . $input);

            throw new UnknownCommandException('Unknown command: ' . $input);
        }

        $command->execute();

        return $command;
    }
}
