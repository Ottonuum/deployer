<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marker;

class MapController extends Controller
{
    public function getMap()
    {
        $markers = Marker::all();
        return view('map', compact('markers'));
    }
}
