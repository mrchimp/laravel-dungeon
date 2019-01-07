<?php

namespace App\Http\Controllers;

use App\Dungeon\Commands\AttackCommand;
use App\Dungeon\Commands\DropCommand;
use App\Dungeon\Commands\GoCommand;
use App\Dungeon\Commands\LookCommand;
use App\Dungeon\Commands\TakeCommand;
use App\Dungeon\Commands\UseCommand;
use Illuminate\Http\Request;

class CmdController extends Controller
{
    public function run(Request $request)
    {
        // look
        // look at
        // go X
        // take X
        // drop X
        // use X
        // use X on X
        // attack X with X

        $input = $request->get('input');
        $chunks = explode(' ', $input);

        $commands = [
            'attack' => AttackCommand::class,
            'drop' => DropCommand::class,
            'go' => GoCommand::class,
            'look' => LookCommand::class,
            'take' => TakeCommand::class,
            'use' => UseCommand::class,
        ];

        if (!in_array($chunks[0], array_keys($commands))) {
            return response()->json([
                'message' => 'I don\'t know how to ' . $chunks[0],
                'success' => false,
            ]);
        }

        $command = new $commands[$chunks[0]];
        $response = $command->execute($input);

        return response()->json([
            'message' => $response,
            'response' => true
        ]);
    }
}
