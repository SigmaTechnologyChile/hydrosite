<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory\Response;

use App\Models\State;
use App\Models\Province;
use App\Models\City;

class AjaxController extends Controller
{
    public function state()
    {
        $states = State::all();
        return response()->json($states);
    }
    
    public function provinces($state_id)
    {
        $states = Province::where('state_id',$state_id)->get();
        return response()->json($states);
    }    
    
    public function cities($province_id)
    {
        $cities = City::where('province_id',$province_id)->get();
        return response()->json($cities);
    }
}
