<?php

namespace App\Http\Controllers;

use Dungeon\CommandRunner;
use Dungeon\Exceptions\UnknownCommandException;
use Illuminate\Http\Request;

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

        try {
            $response = CommandRunner::run($input);
        } catch (UnknownCommandException $e) {
            return response()->json([
                'message' => 'I don\'t know how to ' . $chunks[0],
                'data' => null,
                'success' => false,
            ]);
        }

        return response()->json([
            'message' => $response->getMessage(),
            'data' => $response->getOutputArray(),
            'response' => true
        ]);
    }
}
