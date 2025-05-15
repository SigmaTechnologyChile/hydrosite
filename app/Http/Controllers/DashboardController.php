<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Org;
use Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        dd(Auth::user());
        
        if(Auth::user()->is_manager==1){
            $currentuserid = Auth::user()->id;
            
            $orgs = Org::where('user_id',$currentuserid)->orderby('active','DESC')->paginate(20);
            return view('dashboard-manager');
        }else{
            return view('dashboard');   
        }
    }
}
