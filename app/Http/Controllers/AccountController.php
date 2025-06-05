<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Org;
use App\Models\User;
use App\Models\Member;
use App\Models\Service;
use App\Models\Reading;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        /*if(Auth::user()->is_manager==1){
            $currentuserid = Auth::user()->id;

            $orgs = Org::where('user_id',$currentuserid)->orderby('active','DESC')->paginate(20);
            return view('orgs.index',compact('orgs'));
        }else{*/
            return view('dashboard');
        /*}*/
    }

    public function profile()
    {
        return view('profile');
    }

    public function orgs()
    {
        return view('orgs.index');
    }

    protected function access(Request $request)
    {
        $request->validate([
                'account_name' => ['required']
            ]);
        if(User::where('account_name', $request->input('account_name'))->count()){
            $url = 'https://'.$request->input('account_name').'.rederp.cl';
            return \Redirect::to($url);
        }else{
            abort(404);
        }
    }

  protected function debt($rut)
{
    if(Member::where('rut', $rut)->exists()) {

        $members = Member::where('rut',$rut)->first();


            // Obtiene todas las lecturas impagas del servicio
            $readings = Reading::where('member_id', $members->id)
                                     ->where('payment_status', 0)
                                     ->where('total', '>', 0)
                                     ->orderBy('period', 'DESC')
                                     ->get();



        return view('accounts.debts', compact('rut', 'readings'));
    } else {
        abort(403, 'Rut No encontrado en el sistema, intente con otro o puede comunicarse con soporte@hydrosite.cl');
    }
}

}
