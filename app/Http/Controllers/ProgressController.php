<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index()
    {
        $progressFile = storage_path('app/long-running-command-progress.json');

        if (! file_exists($progressFile)) {
            return response()->json([
                'error' => 'Progress file not found',
            ], 404);
        }

        $progress = json_decode(file_get_contents($progressFile), true);
     //   dd(json($progress));
        return response()->json($progress);
    }
}
