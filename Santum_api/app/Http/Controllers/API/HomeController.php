<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function links(){

        $links = [
            ['name'=>'oahu', 'links'=>'https://shor.by/oahu'],
            ['name'=>'lasvegas', 'links'=>'https://shor.by/lasvegas'],
            ['name'=>'bigislandhawaii', 'links'=>'https://shor.by/bigislandhawaii'],
            ['name'=>'newyorknow', 'links'=>'https://shor.by/newyorknow'],
            ['name'=>'phoenixaz', 'links'=>'https://shor.by/phoenixaz'],
            ['name'=>'destin','links'=>'https://shor.by/destin'],
            ['name'=>'mauinow', 'links'=>'https://shor.by/mauinow'],
            ['name'=>'laketahoenow', 'links'=>'https://shor.by/laketahoenow'],
            ['name'=>'sedona', 'links'=>'https://shor.by/sedona'],
            ['name'=>'Kauai', 'links'=>'https://shor.by/Kauai']
        ];

        $data = [
                'status' => 201,
                'message' => 'Available Limks',
                'links' => $links,
            ];
        return response()->json($data, 200);
    }
}
