<?php

namespace App\Http\Controllers;

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
use Illuminate\Http\Request;
use Log;

class CmdController extends Controller
{
    protected $commands = [
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

    public function run(Request $request)
    {
        $input = $request->get('input');
        $chunks = explode(' ', $input);

        foreach ($this->commands as $command) {
            $command = new $command($input);

            if ($command->matched) {
                break;
            }

            $command = null;
        }

        if (!$command) {
            Log::warning('Unknown command: ' . $input);

            return response()->json([
                'message' => 'I don\'t know how to ' . $chunks[0],
                'data' => null,
                'success' => false,
            ]);
        }

        $command->execute();

        return response()->json([
            'message' => $command->getMessage(),
            'data' => $command->getOutputArray(),
            'response' => true
        ]);
    }
}
