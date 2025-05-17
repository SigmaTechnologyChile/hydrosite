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
        $id        = \Route::current()->Parameter('id');
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

        $sector    = $request->input('sector');
        $search    = $request->input('search');
        $from_date = $request->input('from_date');
        $to_date   = $request->input('to_date');

        if (! $org) {
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

        if (! $org) {
            return redirect()->route('orgs.index')->with('error', 'Organización no encontrada.');
        }

        $start_date = $request->input('start_date');
        $end_date   = $request->input('end_date');
        $sector     = $request->input('sector');
        $search     = $request->input('search');

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
            'current_reading' => 'required|numeric|min:0',
        ]);

        try {
            $org        = $this->org;
            $reading_id = $request->reading_id;

            // Buscar la lectura correspondiente
            if ($reading = Reading::findOrFail($reading_id)) {
                // Actualizar la lectura actual
                $reading->current_reading = $request->current_reading;

                // Calcular la multa basada en los cargos seleccionados
                /*
                if ($request->has('cargo_mora')) {
                    $reading->interest_late = $org->interest_late;
                }
                if ($request->has('cargo_vencido')) {
                    $reading->interest_due = $org->interest_due;
                }
                if ($request->has('cargo_corte_reposicion')) {
                    $reading->replacement_cut = $org->replacement_cut;
                }

                // Si no se seleccionó ninguno de los cargos, pero se proporciona un valor de multa manual
                if ($request->has('other')) {
                    $reading->other = $request->other;
                }*/

                // Guardar la lectura actualizada
                $reading->save();

                return redirect()->route('orgs.readings.index', $id)->with('success', 'Actualización de lectura correcta');
            } else {
                return redirect()->route('orgs.readings.index', $id)->with('warning', 'Lectura no actualizada');
            }
        } catch (\Exception $e) {
            // En caso de error, mostrar un mensaje de error
            return redirect()->route('orgs.readings.index', $id)->with('danger', 'Error al actualizar la lectura: ' . $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'reading_id'      => 'required|numeric',
            'current_reading' => 'required|numeric|min:0',
            'fines'           => 'nullable|numeric|min:0',
        ]);

        try {
            $org        = $this->org;
            $reading_id = $request->reading_id;

            // Buscar la lectura correspondiente
            if ($reading = Reading::findOrFail($reading_id)) {
                // Actualizar la lectura actual
                $reading->previous_reading = $request->previous_reading;
                $reading->current_reading  = $request->current_reading;
                $reading->cm3              = max(0, $reading->current_reading - $reading->previous_reading);
                $service                   = Service::findOrFail($reading->service_id);
                // Calcular la multa basada en los cargos seleccionados

                if ($request->has('cargo_mora')) {
                    $reading->fines = $org->interest_late;
                }
                if ($request->has('cargo_vencido')) {
                    $reading->fines = $org->interest_due;
                }
                if ($request->has('cargo_corte_reposicion')) {
                    $reading->fines = $org->replacement_cut;
                }

                // Si no se seleccionó ninguno de los cargos, pero se proporciona un valor de multa manual
                if ($request->has('other')) {
                    $reading->other = $request->other;
                }

                // calcular total
                $cargo_fijo           = 3000;
                $subsidio             = $service->meter_plan; // 0 o 1
                $porcentaje_subsidio  = $service->percentage / 100;
                $consumo_agua_potable = 0;
                $subsidioDescuento    = 0;
                $cm3                  = $reading->cm3;

                // Definimos los tramos
                $tramoV1 = 500;
                $tramoV2 = 700;
                $tramoV3 = 1300;
                $tramos  = [
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

                    // Log para depuración
                    \Log::info("Service meter_plan: " . $subsidio);
                    \Log::info("Service percentage: " . $service->percentage);
                    \Log::info("print reading? " . $reading);
                    \Log::info("trae subsidio? " . $subsidio);

                    if ($i === 0) {
                        if ($subsidio != 0) {
                            // $precioConSubsidio = $precio * (1 - $porcentaje_subsidio);
                            // $consumo_agua_potable += $cantidad * $precioConSubsidio;
                            // $subsidioDescuento = $cantidad * ($precio - $precioConSubsidio);

                            // \Log::info("precio con subsidio" . $precioConSubsidio);
                                // Aplicar subsidio solo hasta los primeros 13 cm³
                                $cantidadSubvencionada = min(13, $cantidad);
                                $cantidadNormal        = $cantidad - $cantidadSubvencionada;

                                $precioConSubsidio = $precio * (1 - $porcentaje_subsidio);

                                $consumo_agua_potable += $cantidadSubvencionada * $precioConSubsidio;
                                $consumo_agua_potable += $cantidadNormal * $precio;

                                $subsidioDescuento = $cantidadSubvencionada * ($precio - $precioConSubsidio);

                        } else {
                            $consumo_agua_potable += $cantidad * $precio;
                        }
                    } else {
                        // Tramos sin subsidio
                        $consumo_agua_potable += $cantidad * $precio;
                    }

                    $restante -= $cantidad;
                    $anterior = $limite;
                }
                \Log::info("precio subsidio que se descontara " . $subsidioDescuento);
                \Log::info("precio sin subsidio" . $consumo_agua_potable);

                // Asignamos resultados
                $reading->vc_water = $consumo_agua_potable;
                $reading->v_subs   = $subsidioDescuento;

                $subtotal_consumo      = $consumo_agua_potable + $cargo_fijo;
                $reading->total_mounth = $subtotal_consumo;
                $reading->total        = $subtotal_consumo;



                // Log para depuración
                \Log::info("Subtotal de consumo (sin IVA): " . $subtotal_consumo);

                // IVA (si aplica)
                $iva           = 0;
                $total_con_iva = $subtotal_consumo;

                // $routeName = \Route::currentRouteName();
                // if ($routeName === 'orgs.readings.boleta') {
                //     $docType = 'boleta';
                // } elseif ($routeName === 'orgs.readings.factura') {
                //     $docType = 'factura';
                //     // Calcular IVA solo si es factura (19%)
                //     $iva = $subtotal_consumo * 0.19; // IVA sobre el subtotal
                //     $total_con_iva = $subtotal_consumo + $iva;
                // } else {
                //     $docType = 'boleta';
                // }

                // Guardar la lectura actualizada
                $reading->save();

                return redirect()->route('orgs.readings.index', $id)->with('success', 'Actualización de lectura correcta');
            } else {
                return redirect()->route('orgs.readings.index', $id)->with('warning', 'Lectura no actualizada');
            }
        } catch (\Exception $e) {
            // En caso de error, mostrar un mensaje de error
            return redirect()->route('orgs.readings.index', $id)->with('danger', 'Error al actualizar la lectura: ' . $e->getMessage());
        }
    }

    public function dte($id, $readingId)
    {
        \Log::info('Recibiendo solicitud para DTE con ID org: ' . $id . ' y readingId: ' . $readingId);

        $org              = Org::findOrFail($id);
        $reading          = Reading::findOrFail($readingId);
        $reading->member  = Member::findOrFail($reading->member_id);
        $reading->service = Service::findOrFail($reading->service_id);
        $sections         = Section::where('org_id', $id)->OrderBy('from_to', 'ASC')->get();

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
            $v = $section->until_to - $section->from_to;

            if ($consumo >= $v) {
                $section->section = $section->from_to . " Hasta " . $section->until_to;
                $section->m3      = $v;
                $section->precio  = $section->cost;
                $section->total   = $v * $section->cost;
                $consumo          = $consumo - $v;
            } else {
                $section->section = $section->from_to . " Hasta " . $section->until_to;
                $section->m3      = $consumo;
                $section->precio  = $section->cost;
                $section->total   = $consumo * $section->cost;
                $consumo          = 0;
            }
        }

                                                     // Valores fijos
        $cargo_fijo = 3000;                          // Cargo fijo
        $subsidio   = $reading->service->meter_plan; // Subsidio, si existe

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
        $iva           = 0;
        $total_con_iva = $subtotal_consumo; // Inicializamos con el subtotal sin IVA

        $routeName = \Route::currentRouteName();
        if ($routeName === 'orgs.readings.boleta') {
            $docType = 'boleta';
        } elseif ($routeName === 'orgs.readings.factura') {
            $docType = 'factura';
                                                       // Calcular IVA solo si es factura (19%)
            $iva           = $subtotal_consumo * 0.19; // IVA sobre el subtotal
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
