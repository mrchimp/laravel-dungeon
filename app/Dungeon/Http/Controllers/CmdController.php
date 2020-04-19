<?php

namespace Dungeon\Http\Controllers;

use Dungeon\CommandRunner;
use Dungeon\Exceptions\UnknownCommandException;
use Illuminate\Http\Request;

class CmdController extends Controller
{
    public function run(Request $request)
    {
        $input = $request->get('input');

        try {
            $response = CommandRunner::run($input);
        } catch (UnknownCommandException $e) {
            return response()->json([
                'message' => 'I don\'t know how to do that.',
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
