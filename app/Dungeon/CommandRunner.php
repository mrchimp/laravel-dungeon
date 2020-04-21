<?php

namespace Dungeon;

use Dungeon\Commands\AttackCommand;
use Dungeon\Commands\Command;
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
use Dungeon\Commands\LockCommand;
use Dungeon\Commands\LookCommand;
use Dungeon\Commands\RespawnCommand;
use Dungeon\Commands\SayCommand;
use Dungeon\Commands\TakeCommand;
use Dungeon\Commands\UnlockCommand;
use Dungeon\Commands\WhisperCommand;
use Dungeon\Exceptions\UnknownCommandException;
use Illuminate\Support\Facades\Log;

/**
 * Take a given input string, find the appropriate
 * command, run the command and return it.
 */
class CommandRunner
{
    protected static array $commands = [
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
        LockCommand::class,
        LookCommand::class,
        TakeCommand::class,
        RespawnCommand::class,
        GiveCommand::class,
        SayCommand::class,
        UnlockCommand::class,
        WhisperCommand::class,
    ];

    /**
     * Take an input string and run the appropriate command
     *
     * @throws UnknownCommandException
     */
    public static function run(string $input, User $user = null): Command
    {
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

        return $command->execute();
    }
}
