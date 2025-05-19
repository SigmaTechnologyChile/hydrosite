<?php
namespace App\Http\Controllers\Org;

use App\Exports\ReadingsExport;
use App\Exports\ReadingsHistoryExport;
use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Member;
use App\Models\Org;
use App\Models\Reading;
use App\Models\Section;
use App\Models\Service;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReadingController extends Controller
{
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
     * @param int $org_id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($org_id, Request $request)
    {
        $org = $this->org;

        $sector = $request->input('sector');
        $search = $request->input('search');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        if (!$org) {
            return redirect()->route('orgs.index')->with('error', 'Organización no encontrada.');
        }

        $readings = Reading::join('services', 'readings.service_id', 'services.id')
            ->join('members', 'services.member_id', 'members.id')
            ->leftjoin('locations', 'services.locality_id', 'locations.id')
            ->where('readings.org_id', $org_id)
            ->when($sector, function ($q) use ($sector) {
                $q->where('locations.id', $sector);
            })
            ->when($search, function ($q) use ($search) {
                $q->where('members.rut', $search)
                    ->orWhere('members.full_name', 'like', '%' . $search . '%');
            })
            ->when($from_date, function ($q) use ($from_date) {
                $q->whereDate('reading.period', '>=', $from_date);
            })
            ->when($to_date, function ($q) use ($to_date) {
                $q->whereDate('period', '<=', $to_date);
            })
            ->select('readings.*', 'services.nro', 'members.rut', 'members.full_name', 'services.sector as location_name')
            ->orderBy('period', 'desc')->paginate(20);

        $locations = Location::where('org_id', $org->id)->orderby('order_by', 'ASC')->get();

        return view('orgs.readings.index', compact('org', 'readings', 'locations'));
    }

    public function history($org_id, Request $request)
    {
        $org = $this->org;

        if (!$org) {
            return redirect()->route('orgs.index')->with('error', 'Organización no encontrada.');
        }

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $sector = $request->input('sector');
        $search = $request->input('search');

        if ($start_date) {
            $start_date = \Carbon\Carbon::createFromFormat('Y-m-d', $start_date)->format('Y-m');
        }
        if ($end_date) {
            $end_date = \Carbon\Carbon::createFromFormat('Y-m-d', $end_date)->format('Y-m');
        }

        $readings = Reading::join('services', 'readings.service_id', 'services.id')
            ->join('members', 'services.member_id', 'members.id')
            //->leftjoin('locations','services.locality_id','locations.id')
            ->where('readings.org_id', $org_id)
            ->when($start_date && $end_date, function ($q) use ($start_date, $end_date) {
                $q->where('readings.period', '>=', $start_date)
                    ->where('readings.period', '<=', $end_date);
            })
            ->when($sector, function ($q) use ($sector) {
                $q->where('services.locality_id', $sector);
            })
            ->when($search, function ($q) use ($search) {
                $q->where('members.rut', $search)
                    ->orWhere('members.full_name', 'like', '%' . $search . '%');
            })
            ->select('readings.*', 'services.nro', 'members.rut', 'members.full_name', 'services.sector as location_name')
            ->orderBy('period', 'desc')->paginate(20);

        $locations = Location::where('org_id', $org->id)->orderby('order_by', 'ASC')->get();

        return view('orgs.readings.history', compact('org', 'readings', 'locations'));
    }

    public function current_reading_update($id, Request $request)
    {
        $request->validate([
            'reading_id' => 'required|numeric',
            'current_reading' => 'required|numeric|min:0',
        ]);

        try {
            $org = $this->org;
            $reading = Reading::findOrFail($request->reading_id);

            $this->updateReading($org, $reading, $request->only(['current_reading']));

            return redirect()->route('orgs.readings.index', $id)->with('success', 'Lectura actualizada correctamente');
        } catch (\Exception $e) {
            return redirect()->route('orgs.readings.index', $id)->with('danger', 'Error al actualizar lectura: ' . $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'reading_id' => 'required|numeric',
            'current_reading' => 'required|numeric|min:0',
            'fines' => 'nullable|numeric|min:0',
        ]);

        try {
            $org = $this->org;
            $reading = Reading::findOrFail($request->reading_id);

            $this->updateReading($org, $reading, $request->all());

            return redirect()->route('orgs.readings.index', $id)->with('success', 'Actualización de lectura correcta');
        } catch (\Exception $e) {
            return redirect()->route('orgs.readings.index', $id)->with('danger', 'Error al actualizar la lectura: ' . $e->getMessage());
        }
    }

    private function updateReading($org, $reading, $data)
    {
        $reading->previous_reading = $data['previous_reading'] ?? $reading->previous_reading;
        $reading->current_reading = $data['current_reading'];

        $reading->cm3 = max(0, $reading->current_reading - $reading->previous_reading);

        $service = Service::findOrFail($reading->service_id);
        $cargo_fijo = 3000;
        $subsidio = $service->meter_plan; // 0 o 1
        $porcentaje_subsidio = $service->percentage / 100;
        $consumo_agua_potable = 0;
        $subsidioDescuento = 0;
        $cm3 = $reading->cm3;

        $tramoV1 = 500;
        $tramoV2 = 700;
        $tramoV3 = 1300;
        $tramos = [
            ['hasta' => 15, 'precio' => $tramoV1],
            ['hasta' => 30, 'precio' => $tramoV2],
            ['hasta' => PHP_INT_MAX, 'precio' => $tramoV3],
        ];

        $anterior = 0;
        $restante = $cm3;

        for ($i = 0; $i < count($tramos) && $restante > 0; $i++) {
            $limite = $tramos[$i]['hasta'];
            $precio = $tramos[$i]['precio'];

            $cantidad = min($restante, $limite - $anterior);

            if ($i === 0 && $subsidio != 0) {
                $cantidadSubvencionada = min(13, $cantidad);
                $cantidadNormal = $cantidad - $cantidadSubvencionada;
                $precioConSubsidio = $precio * (1 - $porcentaje_subsidio);// esto es el 0.4 o 0.6? es lo que se descuenta o el total, deverisa ser el descuento, osea el 0.4 el que se muestra
                $consumo_agua_potable += $cantidadSubvencionada * $precioConSubsidio;
                $consumo_agua_potable += $cantidadNormal * $precio;
                $subsidioDescuento = $cantidadSubvencionada * ($precio - $precioConSubsidio);
            } else {
                $consumo_agua_potable += $cantidad * $precio;
            }

            $restante -= $cantidad;
            $anterior = $limite;
        }

        $reading->vc_water = $consumo_agua_potable;
        $reading->v_subs = $subsidioDescuento;

        $map = [
            'cargo_mora' => 'interest_late',
            'cargo_vencido' => 'interest_due',
            'cargo_corte_reposicion' => 'replacement_cut',
        ];

        $maxFine = 0;

        foreach ($map as $key => $orgProperty) {
            if (isset($data[$key])) {
                $value = $org->$orgProperty;
                if ($value > $maxFine) {
                    $maxFine = $value;
                }
            }
        }

        $reading->fines = $maxFine;

        $reading->other = $data['other'] ?? $reading->other;

        $subtotal_consumo_mes = $consumo_agua_potable + $cargo_fijo;
        $reading->total_mounth = $subtotal_consumo_mes;
        $subTotal = $subtotal_consumo_mes + $reading->fines + $reading->other + $reading->s_previous;
        $reading->sub_total = $subTotal;

        if ($reading->invoice_type && $reading->invoice_type != "boleta") {
            $iva = $subTotal * 0.19;
            $reading->total = $subTotal + $iva;
        } else {
            $reading->total = $subTotal;
        }

        $reading->save();
    }


    public function dte($id, $readingId)
    {
        \Log::info('Recibiendo solicitud para DTE con ID org: ' . $id . ' y readingId: ' . $readingId);

        $org = Org::findOrFail($id);
        $reading = Reading::findOrFail($readingId);
        $reading->member = Member::findOrFail($reading->member_id);
        $reading->service = Service::findOrFail($reading->service_id);
        $sections = Section::where('org_id', $id)->OrderBy('from_to', 'ASC')->get();

        if ($sections->isEmpty()) {
            \Log::error("No se encontraron secciones para la organización con ID: {$id}");
            abort(404, 'No se encontraron secciones.');
        }

        // Asegurándonos de que el consumo es mayor que 0
        $consumo = $reading->cm3;
        \Log::info("Consumo inicial: " . $consumo);

        $detalle_sections = [];
        $consumo_restante = $consumo;

        foreach ($sections as $section) {
            $tramo_desde = $section->from_to;
            $tramo_hasta = $section->until_to;
            $v = $section->until_to - $section->from_to + 1;

            $m3_en_este_tramo = min($v, $consumo);
            if ($consumo >= $v) {
                $section->section = $section->from_to . " Hasta " . $section->until_to;
                $section->m3 = $m3_en_este_tramo;//variable para el boleta.blade
                $section->precio = $section->cost;
                $section->total = $consumo * $section->cost;
                $consumo = $reading->cm3 - $m3_en_este_tramo;
            } else {
                $section->section = $section->from_to . " Hasta " . $section->until_to;
                $section->m3 = $m3_en_este_tramo;//variable para el boleta.blade
                $section->precio = $section->cost;
                $section->total = $consumo * $section->cost;
                $consumo = 0;
            }
        }

        // Valores fijos
        $cargo_fijo = 3000;                          // Cargo fijo
        $subsidio = $reading->service->meter_plan; // Subsidio, si existe

        // Aquí calculamos el subtotal de consumo (con tramos)
        // $consumo_agua_potable = $detalle_sections ? array_sum(array_column($detalle_sections, 'total')) : 0;
        $consumo_agua_potable = 0;

        for ($i = 0; $i < count($detalle_sections); $i++) {
            $valor = $detalle_sections[$i]['total'] ?? 0;

            if ($i === 0) {
                if ($subsidio != 0) {
                    $valor = $detalle_sections[$i]['total'] / $subsidio;
                }
            }
            $consumo_agua_potable += $valor;
        }

        $subtotal_consumo = $consumo_agua_potable + $cargo_fijo;

        // Verificando el subtotal
        \Log::info("Subtotal de consumo (sin IVA): " . $subtotal_consumo);

        // Definir el IVA solo si el tipo de documento es factura
        $iva = 0;
        $total_con_iva = $subtotal_consumo; // Inicializamos con el subtotal sin IVA

        $routeName = \Route::currentRouteName();
        if ($routeName === 'orgs.readings.boleta') {
            $docType = 'boleta';
        } elseif ($routeName === 'orgs.readings.factura') {
            $docType = 'factura';
            // Calcular IVA solo si es factura (19%)
            $iva = $subtotal_consumo * 0.19; // IVA sobre el subtotal
            $total_con_iva = $subtotal_consumo + $iva;
        } else {
            $docType = 'boleta';
        }

        \Log::info('Tipo de documento seleccionado: ' . $docType);
        \Log::info("IVA Calculado: {$iva}");
        \Log::info("Total con IVA: {$total_con_iva}");

        switch (strtolower($docType)) {
            case 'boleta':
                \Log::info('Entrando a la vista de Boleta');
                return view('orgs.boleta', compact('reading', 'org', 'detalle_sections', 'sections', 'subtotal_consumo', 'total_con_iva'));

            case 'factura':
                \Log::info('Entrando a la vista de Factura');
                return view('orgs.factura', compact('reading', 'org', 'detalle_sections', 'sections', 'subtotal_consumo', 'iva', 'total_con_iva'));
            //$pdf = Pdf::loadView('orgs.factura', compact('reading', 'org', 'detalle_sections', 'sections', 'subtotal_consumo', 'iva', 'total_con_iva'));
            //return $pdf->stream();

            default:
                abort(404, 'Tipo de documento no reconocido: ' . $docType);
        }
    }

    /*Export Excel*/
    public function export()
    {
        return Excel::download(new ReadingsExport, 'Reading-' . date('Ymdhis') . '.xlsx');
    }

    public function exportHistory($id)
    {
        return Excel::download(new ReadingsHistoryExport, 'Readings-History-' . date('Ymdhis') . '.xlsx');
    }

}
