<?php

namespace App\Http\Controllers;

use App\Dungeon\Commands\AttackCommand;
use App\Dungeon\Commands\DropCommand;
use App\Dungeon\Commands\EatCommand;
use App\Dungeon\Commands\EquipCommand;
use App\Dungeon\Commands\GoCommand;
use App\Dungeon\Commands\HelpCommand;
use App\Dungeon\Commands\HiCommand;
use App\Dungeon\Commands\InspectCommand;
use App\Dungeon\Commands\InventoryCommand;
use App\Dungeon\Commands\KillCommand;
use App\Dungeon\Commands\LookCommand;
use App\Dungeon\Commands\RespawnCommand;
use App\Dungeon\Commands\TakeCommand;
use App\Dungeon\Commands\UseCommand;
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
        // UseCommand::class,
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
