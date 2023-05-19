<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function links(){

        $links = [
           'name' => 'someone',
            
        ];

        $data = [
                'status' => 201,
                'message' => 'Available Limks',
                'links' => $links,
            ];
        return response()->json($data, 200);
    }
}
