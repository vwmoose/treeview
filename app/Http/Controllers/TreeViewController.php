<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class TreeViewController extends Controller
{
    /**
     * index
     * 
     * @return Response
     */
    public function index(Request $request): Response
    {
        // define
        $json = null;

        $filename = 'sample2-json.json';

        // json exists
        if (Storage::exists($filename)) {
            // retrieve the json
            $json = json_decode(Storage::get($filename), true);
        }

        return Inertia::render(
            'TreeView/Index', 
            [
                'canLogin' => false,
                'canRegister' => false,
                'json' => $json,
            ]
        );
    }
}
