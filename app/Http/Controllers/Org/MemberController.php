<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MembersExport;
use App\Models\State;
use App\Models\City;
use App\Models\Location;
use App\Models\User;
use App\Models\Org;
use App\Models\OrgMember;
use App\Models\Member;
use App\Models\Service;
use App\Models\MemberService;



class MemberController extends Controller
{
    protected $_param;
    public $org;
    
    public function __construct()
    {
        $this->middleware('auth');
        $id = \Route::current()->Parameter('id');
        $this->org = Org::find($id);
    }

    public function index(Request $request)
    {
        $org = $this->org;
        $sector = $request->input('sector');
        $search = $request->input('search');
       
        $members = Member::join('orgs_members','members.id','orgs_members.member_id')
                        ->leftjoin('services','members.rut','services.rut')
                        ->where('orgs_members.org_id',$org->id)
                        //->where('services.org_id',$org->id)
                        ->when($sector, function($q) use ($sector) {
                                $q->where('services.locality_id',$sector);
                        }) 
                        ->when($search, function($q) use ($search) {
                                $q->where('members.rut', $search)
                                  ->orWhere('members.first_name', 'like', '%' . $search . '%')
                                  ->orWhere('members.last_name', 'like', '%' . $search . '%')
                                  ->orWhere('members.full_name', 'like', '%' . $search . '%');
                        })
                        ->select('members.*',DB::raw('count(*) as qrx_serv'))
                        ->groupby('members.rut')
                        ->paginate(20);
                            
        $locations = Location::where('org_id',$org->id)->OrderBy('order_by','ASC')->get();              
        return view('orgs.members.index', compact('org', 'members','locations'));
    }
    
    public function create()
    {
        $org = $this->org;
        $states = State::all();
        $locations = Location::where('org_id',$org->id)->OrderBy('order_by','ASC')->get();
        return view('orgs.members.create', compact('org','states','locations'));
    }
    
    public function store(Request $request, $orgId)
    {
        
        //dd($request->all());
        $org = $this->org;

        $rules = [
        'rut' => 'required',
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email',
        'address' => 'required',
        'city' => 'required',
        'mobile_phone' => 'nullable',
        'phone' => 'nullable',
        'partner' => 'required',
        'gender' => 'required|string',
        'active' => 'nullable|boolean',
        ];

    
        if ($request->has('activo')&& $request->input('activo') == 1) {
            $rules = array_merge($rules, [
                'locality_id' => 'required|numeric',
                'meter_plan' => 'required|in:si,no',
                'percentage' => 'required|numeric|min:1|max:100',
                'meter_type' => 'required',
                'meter_number' => 'required',
                'invoice_type' => 'required',
                'diameter' => 'required',
            ]);
        }

    
        $validated = $request->validate($rules);
    
       // Crear el miembro
        $member = new Member();
        $member->rut = $validated['rut'];  
        $member->first_name = $validated['first_name'];  
        $member->last_name = $validated['last_name'];  
        $member->full_name = $validated['first_name'] . ' ' . $validated['last_name']; 
        $member->gender = $validated['gender']; 
        $member->email = $validated['email'];
        $member->address = $validated['address'];  
        $member->city_id = $validated['city'];
        $member->partner = $validated['partner'];  
        $member->phone = $validated['phone'] ?? null;  
        $member->mobile_phone = $validated['mobile_phone'] ?? null;  
        $member->active = $validated['activo'] ?? 1;
        $member->save();
    
        
        if ($request->has('activo') && $request->input('activo') == 1) {
            $lastService = Service::max('nro');  // Obtener el último 'nro' de servicio
            $newNro = $lastService ? $lastService + 1 : 1;  
            
            $service = new Service(); 
            $service->member_id = $member->id;
            $service->nro = $newNro;
            $service->locality_id = $validated['locality_id'];
            $service->meter_plan = $validated['meter_plan'];
            $service->percentage = $validated['percentage'];
            $service->meter_type = $validated['meter_type'];
            $service->meter_number = $validated['meter_number'];
            $service->invoice_type = $validated['invoice_type'];
            $service->diameter = $validated['diameter'];
            $service->observations = $request->input('observations');
            $service->save();
        }
        $OrgMember = new OrgMember();
        $OrgMember->org_id = $org->id;
        $OrgMember->member_id = $member->id;
        $OrgMember->save();
        
        return redirect()->route('orgs.members.index', $org->id)->with('success', 'Socio creado correctamente.');
    }
    
    private function saveServiceData($memberId, Request $request, $validated)
    {
        
    $lastService = Service::where('member_id', $memberId)
                          ->where('org_id', $orgId) 
                          ->orderBy('nro', 'desc')
                          ->first();

    
    $newNro = $lastService ? $lastService->nro + 1 : 1;
        
        // Crear un nuevo registro de servicio solo si el miembro está activo
        $service = new MemberService();
        $service->member_id = $memberId;
        $service->org_id = $orgId;
        $service->meter_plan = $validated[meter_plan];
        $service->percentage = $request->percentage;
        $service->meter_type = $request->meter_type;
        $service->meter_number = $request->meter_number;
        $service->invoice_type = $request->invoice_type;
        $service->diameter = $request->diameter;
        $service->partner = $request->input('partner', 'cliente');
        $service->observations = $request->observations;
        $service->nro = $newNro;
        $service->save();
    }
    
    public function dashboard($id)
    {
        $org = $this->org;
        return view('orgs.dashboard', compact('org'));
    }

    public function edit($orgId, $memberId)
    {
        $org = $this->org;
        $member = Member::findOrFail($memberId);
        if($member->city_id){
            $city = City::join('provinces','cities.province_id','provinces.id')->join('states','provinces.state_id','states.id')->find($member->city_id);
        }else{
            $city=array();
        }
        $services = Service::where('member_id', $memberId)->get();
        $states = State::all();
        //$states = State::all();
        return view('orgs.members.edit', compact('org', 'member', 'services','states','city'));
    }
    
    public function update(Request $request, $orgId, $memberId)
{

    $validated = $request->validate([
        'rut' => 'required|unique:members,rut,' . $memberId,
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'commune' => 'nullable|string|max:255',
        'state' => 'required|string|max:255'
        
    ]);

    $org = Org::findOrFail($orgId);
    $member = Member::findOrFail($memberId);


    $member->rut = $validated['rut'];
    $member->first_name = $validated['first_name'];
    $member->last_name = $validated['last_name'];
    $member->full_name = $validated['first_name'] . ' ' . $validated['last_name'];
    $member->address = $validated['address'];
    $member->commune = $validated['commune'] ?? '';  
    $member->state = $validated['state'];

    
    $member->save();

    
    return redirect()->route('orgs.members.index', $org->id)->with('success', 'Miembro actualizado correctamente.');

}

    private function updateServiceData($memberId, Request $request)
    {
        
        $service = Service::where('member_id', $memberId)->first();
    
        
        if ($service) {
            $service->sector = $request->input('sector');
            $service->meter_plan = $request->input('meter_plan');
            $service->percentage = $request->input('percentage');
            $service->meter_type = $request->input('meter_type');
            $service->meter_number = $request->input('meter_number');
            $service->invoice_type = $request->input('invoice_type');
            $service->diameter = $request->input('diameter');
            $service->observations = $request->input('observations');
            $service->save();
        }
    }
    
    public function transferService(Request $request, $orgId, $memberId, $serviceId)
    {
        try {
            
            $request->validate([
                'new_rut' => 'required|exists:members,rut',  // Validar que el nuevo RUT exista en la tabla de miembros
            ]);
    
            $org = Org::findOrFail($orgId);
            $member = Member::findOrFail($memberId);
    
            
            $service = Service::where('id', $serviceId)
                              ->where('member_id', $memberId)
                              ->first();
    
            if (!$service) {
                return redirect()->back()->with('error', 'Servicio no encontrado o no asociado a este miembro.');
            }
    
            
            $newMember = Member::where('rut', $request->new_rut)->firstOrFail();  
    
            $service->member_id = $newMember->id;
            $service->save();
           
            return redirect()->route('orgs.members.services.index', [$orgId, $newMember->id]) 
                             ->with('success', 'Servicio transferido correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al transferir el servicio. Inténtalo de nuevo.');
        }
    }
    
    public function editService($orgId, $memberId, $serviceId)
    {
        $org = Org::findOrFail($orgId);
        $member = Member::findOrFail($memberId);
        $service = Service::findOrFail($serviceId);
        
        $sectors = Location::where('org_id', $orgId)->get();
    
        return view('orgs.members.editservice', compact('org', 'member', 'service', 'sectors'));
    }
    
    public function updateService(Request $request, $orgId, $memberId, $serviceId)
    {
        $validated = $request->validate([
            'sector' => 'required|string',
            'meter_plan' => 'required|in:si,no',
            'meter_type' => 'required|in:analogico,digital',
            'meter_number' => 'required|string',
            'invoice_type' => 'required|in:boleta,factura',
            'diameter' => 'required|in:1/2,3/4',
        ]);
        
        // Si el 'meter_plan' es 'si', valida 'percentage'
        if ($request->input('meter_plan') === 'si') {
            $validated['percentage'] = $request->validate([
                'percentage' => 'nullable|numeric|min:0|max:100',
            ])['percentage'] ?? null;  // Si 'percentage' no está presente, se asigna null
        } else {
            // Si 'meter_plan' es 'no', asigna un valor por defecto a 'percentage'
            $validated['percentage'] = 0; // Asignar 0 en lugar de null
        }
        
        $service = Service::findOrFail($serviceId);
   
        $service->sector = $validated['sector'];
        $service->meter_plan = $validated['meter_plan'];
        $service->percentage = $validated['percentage'];
        $service->meter_type = $validated['meter_type'];
        $service->meter_number = $validated['meter_number'];
        $service->invoice_type = $validated['invoice_type'];
        $service->diameter = $validated['diameter'];

        // Manejo específico del estado activo
        $service->active = $request->has('active') ? 1 : 0;
    
        $service->save();
    
    
        return redirect()->route('orgs.members.edit', [$orgId, $memberId])
            ->with('success', 'Servicio actualizado correctamente.');
    }
    
    public function toggleStatus($orgId, $memberId, $serviceId)
    {
        // Buscar el servicio por su ID
        $service = Service::findOrFail($serviceId);

        // Cambiar el estado del servicio (activo/desactivado)
        $service->active = !$service->active;
        $service->save();

        // Redirigir de vuelta al detalle del miembro con un mensaje de éxito
        return redirect()->route('orgs.members.edit', [$orgId, $memberId])
                         ->with('success', 'Estado del servicio actualizado exitosamente.');
    }
    
    
    public function export() 
    {
        return Excel::download(new MembersExport, 'Members-'.date('Ymdhis').'.xlsx');
    }
}