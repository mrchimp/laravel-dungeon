<?php

namespace App\Http\Controllers;

use App\Dungeon\Commands\LookCommand;
use App\Dungeon\Commands\GetCommand;
use App\Dungeon\Commands\AttackCommand;
use Illuminate\Http\Request;

class CmdController extends Controller
{
    public function run(Request $request)
    {
        // look
        // get X
        // use X
        // use X on X
        // attack X with X

        $input = $request->get('input');
        $chunks = explode(' ', $input);

        $commands = [
            'look' => LookCommand::class,
            'get' => GetCommand::class,
            'use' => UseCommand::class,
            'attack' => AttackCommand::class,
        ];

        if (!in_array($chunks[0], array_keys($commands))) {
            return response()->json([
                'success' => false,
            ]);
        }

        $command = new $commands[$chunks[0]];
        $response = $command->run($input);

        return response()->json([
            'message' => $response,
            'response' => true
        ]);
    }
}
