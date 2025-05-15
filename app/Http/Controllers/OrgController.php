<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use App\Models\User;
use App\Models\Org;
use App\Models\Reading;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class OrgController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $_param;
    public $org;
    
    public function __construct()
    {
        $this->middleware('auth');
        $id = \Route::current()->Parameter('id');
        $this->org = Org::find($id);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->is_manager==1){
            $currentuserid = Auth::user()->id;
            $orgs = Org::orderby('active','DESC')->paginate(20);
            //$orgs = Org::where('user_id',$currentuserid)->orderby('active','DESC')->paginate(20);
        }else{
            $orgs = Org::orderby('active','DESC')->paginate(20);
        }
        return view('orgs.index',compact('orgs'));
    }
    
    public function create()
    {
        return view('orgs.create');
    }
    
    public function dashboard(Request $request, $id)
    {
        $org = $this->org;
        // Obtener el año y mes de la solicitud, con valores predeterminados
        $year = $request->input('year', date('Y')); 
        
        //$org->m3_consume = Reading::where('period','like','%'.$year.'%')->selectRaw('substring_index(period, "-", -1) as mes,sum(cm3) as cm3')->groupby('period')->get();
        //dd($org->m3_consume);
        
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];
        
        $m3_consume = array();
        $payment_consume = array();
        $total_recaudado = array();
        $totalExtraido = array();
        $totalPerdida = array();
        $payment_status_count = array();
        $labels = []; 
        
        for($i =1; $i < 13;$i++){
            $month = str_pad($i, 2, 0, STR_PAD_LEFT);
            $period_like = "%{$year}-{$month}%";
            
            if ($month && $month != $i) {
                continue; 
            }
   
            $m3_consume[$i] = Reading::where('org_id', $org->id)->where('period','like','%'.$year.'-'.str_pad($i,2,0,STR_PAD_LEFT).'%')->sum('cm3');   
            $payment_consume[$i] = Reading::where('org_id', $org->id)->where('period','like','%'.$year.'-'.str_pad($i,2,0,STR_PAD_LEFT).'%')->sum('total');
            $total_recaudado[$i] = Reading::where('org_id', $org->id)->where('period', 'like', $period_like)
                                      ->where('payment_status', 1)
                                      ->sum('total');
                                      
            // Lógica para Total Extraído / Total Pérdida
            $totalExtraido[$i-1] = floatval($m3_consume[$i]); 
            $totalPerdida[$i-1] = floatval($m3_consume[$i] * 0.2);                     
                                                  
            // Contar cuántos con payment_status = 1 y = 0
            $payment_status_count[$i] = [
                    'pagado' => Reading::where('org_id', $org->id)->where('period', 'like', $period_like)->where('payment_status', 1)->count(),
                    'no_pagado' => Reading::where('org_id', $org->id)->where('period', 'like', $period_like)->where('payment_status', 0)->count()
            ];
            $labels[] = $meses[$i];
        }
        
        $ultimoPeriodo = Reading::where('org_id', $org->id)->max('period');

        $locations = Location::where('org_id', $org->id)->get();
        
        
        $sector_consumo = [];
        
        foreach ($locations as $location) {
            $consumoMes = Reading::where('org_id', $org->id)
                ->where('locality_id', $location->id)
                ->where('period', $ultimoPeriodo)
                ->sum('cm3');
        
            $consumoAnio = Reading::where('org_id', $org->id)
                ->where('locality_id', $location->id)
                ->where('period', 'like', $year.'-%')
                ->sum('cm3');
        
            $location->consumo_m3_mes = $consumoMes;
            $location->consumo_m3_anio = $consumoAnio;
        
            $sector_consumo[] = [
                'sector' => $location->name,
                'consumo_mes' => $consumoMes,
                'consumo_anio' => $consumoAnio
            ];
        }
        $deudores = [];
        // Deudores sin multa (30 días)
        $deudores[0] = Reading::where('org_id', $org->id)
            ->where('period', $ultimoPeriodo)
            ->where('payment_status', 0)
            ->where('fines', 0) // Sin multas
            ->count();
        // Deudores con multa (60 días)
        $deudores[1] = Reading::where('org_id', $org->id)
            ->where('period', $ultimoPeriodo)
            ->where('payment_status', 0)
            ->where('fines', '>', 0) // Con multas
            ->count();
        // Deudores con corte (90 días) 
        $deudores[2] = Reading::where('org_id', $org->id)
            ->where('period', $ultimoPeriodo)
            ->where('payment_status', 0)
            ->where('fines', '>', 0) // Con multas
            ->where('other', '>', 0) // Asumiendo que "other" indica corte
            ->count();    
            
        //dd($m3_consume);
        return view('orgs.dashboard',compact('org','m3_consume','payment_consume', 'total_recaudado', 'payment_status_count', 'sector_consumo', 'ultimoPeriodo', 'totalExtraido', 'totalPerdida',  'labels', 'deudores',
        'meses', 'year', 'month'
        ));
    }
    
    public function fixed_charge_store($id,Request $request)
    {
        try {
            $fixed_charge = $request->input('fixed_charge');
            $interest_due = $request->input('interest_due');
            $interest_late = $request->input('interest_late');
            $replacement_cut = $request->input('replacement_cut');
        
            if($org=  Org::find($id)){
                $org->fixed_charge=$fixed_charge;
                $org->interest_due=$interest_due;
                $org->interest_late=$interest_late;
                $org->replacement_cut=$replacement_cut;
                $org->save();

                return redirect()->route('orgs.sections.index',$id)->with('success', 'Valores estandar actualizados');
	        }else{
	            return redirect()->route('orgs.sections.index',$id)->with('warning', 'Valores estandar no actualizados');
	        }
        } catch (\Exception $e) {
            //return response()->json(['message'=>'payment not found!'.$e], 404);
            return redirect()->route('orgs.sections.index',$id)->with('danger', 'not found!'.$e);
        }
    }
}
