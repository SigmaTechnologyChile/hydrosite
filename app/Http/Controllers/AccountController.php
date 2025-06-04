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
        $services = Service::where('rut', $rut)->get();

        foreach ($services as $service) {
            // Obtiene todas las lecturas impagas del servicio
            $unpaidReadings = Reading::where('service_id', $service->id)
                                     ->where('payment_status', 0)
                                     ->orderBy('period', 'DESC')
                                     ->get();

            // Asigna la colección al servicio como propiedad dinámica
            $service->unpaid_readings = $unpaidReadings;

            // Define el estado general del servicio
            if ($unpaidReadings->isNotEmpty()) {
                $service->payment_status = 'Pendiente';
            } else {
                $service->payment_status = 'Sin deuda pendiente';
            }
        }

        return view('accounts.debts', compact('rut', 'services'));
    } else {
        abort(403, 'Rut No encontrado en el sistema, intente con otro o puede comunicarse con soporte@hydrosite.cl');
    }
}

}
