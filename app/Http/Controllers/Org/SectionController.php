<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Org;
use App\Models\Section;
use App\Exports\SectionsExport;
use Maatwebsite\Excel\Facades\Excel;

class SectionController extends Controller
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
        $sections = Section::where('org_id',$org->id)->paginate(20);
        return view('orgs.sections.index',compact('org','sections'));
    }
    
    public function create()
    {
        $org = $this->org;
        return view('orgs.sections.create',compact('org'));
    }
    
    public function store($orgId , Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'from_to' => 'required|numeric|min:0',
            'until_to' => 'required|numeric|gte:from_to',
            'cost' => 'required|numeric|min:0',
        ]);
    
        $section = new Section;
        $section->org_id = $orgId;
        $section->name = $request->name;
        $section->from_to = $request->from_to;
        $section->until_to = $request->until_to;
        $section->cost = $request->cost;
        $section->save();
    
        return redirect()->route('orgs.sections.index', $orgId)
                         ->with('success', 'Tramo creado correctamente.');
    }
    
   public function edit($orgId, $tramoId)
    {
        $org = Org::findOrFail($orgId);
        $section = Section::findOrFail($tramoId);
    
        return view('orgs.sections.edit', compact('org', 'section'));
    }
    
    public function update(Request $request, $orgId, $tramoId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'from_to' => 'required|numeric|min:0',
            'until_to' => 'required|numeric|gte:from_to',
            'cost' => 'required|numeric|min:0',
        ]);
    
        $section = Section::findOrFail($tramoId);
    
        // Asignación campo por campo
        $section->name = $request->name;
        $section->from_to = $request->from_to;
        $section->until_to = $request->until_to;
        $section->cost = $request->cost;
    
        $section->save();
    
        return redirect()->route('orgs.sections.index', $orgId)
                         ->with('success', 'Tramo actualizado correctamente.');
    }
    
    /*Export Excel*/
    public function export() 
    {
        return Excel::download(new SectionsExport, 'Section-'.date('Ymdhis').'.xlsx');
    }
}
