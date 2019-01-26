<?php

namespace App\Http\Controllers;

use App\Dungeon\Commands\AttackCommand;
use App\Dungeon\Commands\DropCommand;
use App\Dungeon\Commands\EatCommand;
use App\Dungeon\Commands\EquipCommand;
use App\Dungeon\Commands\GoCommand;
use App\Dungeon\Commands\InspectCommand;
use App\Dungeon\Commands\InventoryCommand;
use App\Dungeon\Commands\LookCommand;
use App\Dungeon\Commands\TakeCommand;
use App\Dungeon\Commands\UseCommand;
use Illuminate\Http\Request;
use Log;

class CmdController extends Controller
{
    public function run(Request $request)
    {
        $input = $request->get('input');
        $chunks = explode(' ', $input);

        $commands = [
            'attack' => AttackCommand::class,
            'drop' => DropCommand::class,
            'eat' => EatCommand::class,
            'equip' => EquipCommand::class,
            'go' => GoCommand::class,
            'inspect' => InspectCommand::class,
            'inventory' => InventoryCommand::class,
            'look' => LookCommand::class,
            'take' => TakeCommand::class,
            // 'use' => UseCommand::class,
        ];

        if (!in_array($chunks[0], array_keys($commands))) {
            Log::warning('Unknown command: ' . $input);

            return response()->json([
                'message' => 'I don\'t know how to ' . $chunks[0],
                'data' => null,
                'success' => false,
            ]);
        }

        $command = new $commands[$chunks[0]];
        $command->execute($input);

        return response()->json([
            'message' => $command->getMessage(),
            'data' => $command->getOutputArray(),
            'response' => true
        ]);
    }
}
