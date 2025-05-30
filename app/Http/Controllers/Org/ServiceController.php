<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Org;
use App\Models\Service;
use App\Models\Member;
use App\Models\Location;
use Validator;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ServiceExport;
use App\Models\State;

class ServiceController extends Controller
{
    protected $_param;
    public $org;

    public function __construct()
    {
        $this->middleware('auth');
        $id = \Route::current()->Parameter('id');
        $this->org = Org::find($id);
    }

    public function index()
    {
        $org = $this->org;
        $locations = Location::all();
        $services = DB::table('services')
            ->join('members', 'services.member_id', '=', 'members.id')
            ->join('locations', 'services.locality_id', '=', 'locations.id')
            ->where('services.org_id', $org->id)
            ->when(request()->sector, function ($query) {
                return $query->where('services.sector', 'like', '%' . request()->sector . '%');
            })
            ->when(request()->nro, function ($query) {
                return $query->where('services.nro', 'like', '%' . request()->nro . '%');
            })
            ->select(
                'services.*',
                'members.full_name as member_name',
                'members.rut as member_rut',
                'locations.name as location_name'
            )
            ->paginate(21);

        return view('orgs.services.index', compact('org', 'services', 'locations'));
    }

    public function create()
    {
        return view('orgs.services.create');
    }


    public function createForMember($orgId, $memberId)
    {
        $org = $this->org;
        $locations = Location::where('org_id',$this->org->id)->get();
        $member = Member::findOrFail($memberId);
        $states = State::orderBy('name_state')->get();
         return view('orgs.services.create', compact('org', 'member','locations', 'states'));
    }

    public function storeForMember(Request $request, $orgId, $memberId)
    {

        $validator = Validator::make($request->all(), [
            'locality_id' => 'required|integer',
            'meter_plan' => 'required',
            'meter_type' => 'required|in:analogico,digital',
            'meter_number' => 'required|string|max:20',
            'invoice_type' => 'required|in:boleta,factura',
            'diameter' => 'required|in:1/2,3/4',
            'partner' => 'nullable|in:socio,cliente',
            'observations' => 'nullable|string|max:1000',
            'state' => 'nullable|string|max:255',
            'commune' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($request->meter_plan == '1') {
            $validator->addRules(['percentage' => 'required|numeric|min:0|max:100']);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        } else {
            $request->merge(['percentage' => 0]);
        }


        $member = Member::findOrFail($memberId);


        $serviceState = State::findOrFail($request->service_state);
        $serviceStateName = $serviceState->name_state;


        $lastServiceNro = Service::where('org_id', $orgId)
            ->lockForUpdate()
            ->max('nro');


        $newNroNumber = $lastServiceNro ? (int)$lastServiceNro + 1 : 1;
        $newNro = (string)$newNroNumber;

        $states = State::orderBy('name_state')->get();


        while (Service::where('nro', $newNro)->where('org_id', $orgId)->exists()) {
            $newNroNumber++;
            $newNro = (string)$newNroNumber;
        }

        $service = new Service();
        $service->member_id = $memberId;
        $service->org_id = $orgId;
        $service->locality_id = $request->locality_id;
        $service->meter_plan = $request->meter_plan;
        $service->percentage = $request->percentage ?: 0;
        $service->meter_type = $request->meter_type;
        $service->meter_number = $request->meter_number;
        $service->invoice_type = $request->invoice_type;
        $service->diameter = $request->diameter;


        $service->observations = $request->observations;
        $service->rut = $member->rut;
        $service->address = $member->address;
        $service->state = $request->state ?: '';
        $service->commune = $request->commune ?: '';
        $maxNro = Service::where('org_id',$orgId)->max('nro');
        $service->nro = $maxNro + 1;

        while (Service::where('org_id',$orgId)->where('nro', $service->nro)->exists()) {
        $service->nro++;
        }
        $service->active = 1;
        $service->save();

        return redirect()->route('orgs.members.index', ['id' => $orgId])
            ->with('success', 'Servicio creado exitosamente');
    }

    public function export($id)
    {
        return Excel::download(new ServiceExport, 'Servicios-'.date('Ymdhis').'.xlsx');
    }
}
