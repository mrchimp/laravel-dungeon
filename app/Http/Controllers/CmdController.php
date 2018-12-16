<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CmdController extends Controller
{
    public function run(Request $request)
    {
        return response()->json([
            'output' => 'Ok.',
        ]);
    }
}
