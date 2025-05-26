@extends('layouts.nice', ['active' => 'readings.boleta', 'title' => 'Boleta de Consumo'])

@section('content')
<div class="container py-3" style="max-width: 800px;">
        <div class="mb-3 d-print-none">
        <a href="{{ route('orgs.readings.index', $org->id) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver a Lecturas
        </a>
    </div>
    <div class="border p-3 rounded shadow-sm bg-light">
        <div class="row">
            <!-- Logo  e información-->
            <div class="col-md-8">
                <div class="d-flex align-items-center mb-2">
                    <img src="{{ $org->logo }}" alt="Logo" width="150" height="150" class="me-3">

                    <div>
                        <h5 class="fw-bold text-primary mb-0">COMITÉ AGUA POTABLE RURAL LAS ARBOLEDAS</h5>
                        <p class="mb-0 small">Captación, Purificación y Venta de agua potable</p>
                        <p class="mb-0 small">Callejón La Caja S/N Las Arboledas - Teno</p>
                        <p class="mb-0 small">Cel: +569 5858 6377</p>
                        <p class="mb-0 small">Fono Emergencia: +569 8646 8372</p>
                        <p class="mb-0 small">Correo: comiteaprlasarboledas@gmail.com</p>
                    </div>
                </div>
            </div>

            <!-- RUT -->
            <div class="col-md-4">
                <div class="border border-danger p-2 text-center">
                    <p class="mb-1 fw-bold">RUT 71.108.700-1</p>
                    <p class="mb-1 text-danger fw-bold">BOLETA NO AFECTA O<br>EXENTA ELECTRÓNICA</p>
                    <p class="mb-1 fw-bold">N° {{$reading->folio}}</p>
                    <p class="mb-1">S.I.I. CURICO</p>
                    <p class="mb-0">Fecha Emisión: {{ \Carbon\Carbon::parse($reading->fecha_lectura)->format('d F, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- alerta -->
        <div class="alert alert-info text-center py-1 my-2">
            Recuerda proteger tu medidor... en tiempos de invierno
        </div>

        <!--información del cliente -->
        <div class="row mb-2">
            <div class="col-md-7">
                <table class="table table-sm table-bordered mb-0">
                    <tr>
                        <td class="fw-bold" style="width: 120px;">RUT</td>
                        <td>{{ $reading->member->rut ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">NOMBRE</td>
                        <td>{{ $reading->member->full_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">DIRECCIÓN</td>
                        <td>{{ $reading->member->address ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">SECTOR</td>
                        <td>{{ $reading->service->sector ?? '' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-5">
                <table class="table table-sm table-bordered mb-0">
                    <tr>
                        <td class="fw-bold">SERVICIO</td>
                        <td>{{ str_pad($reading->service->nro, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">MIDEPLAN</td>
                       @if ($reading->service->meter_plan && $reading->service->meter_plan == 1  )
                         <td> Sí  </td>
                        @else
                     <td>  No </td>
                    @endif

                    </tr>
                    <tr>
                        <td class="fw-bold">DIÁMETRO</td>
                        <td>{{ $reading->service->diameter ?? '1/2' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">MEDIDOR</td>
                        <td>{{ $reading->service->meter_type ?? '' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-6">
                <div class="bg-primary text-white p-1 text-center fw-bold">
                    Su detalle de consumo en M3
                </div>
                <table class="table table-sm table-bordered mb-0">
                    <tr>
                        <td>Lectura Actual</td>
                        <td class="text-end">{{ \Carbon\Carbon::parse($reading->fecha_lectura)->format('d-m-Y') }}</td>
                        <td class="text-end">{{ $reading->current_reading }}</td>
                    </tr>
                    <tr>
                        <td>Lectura Anterior</td>
                        <td class="text-end">{{ \Carbon\Carbon::parse($reading->fecha_lectura)->subMonth()->format('d-m-Y') }}</td>
                        <td class="text-end">{{ $reading->previous_reading }}</td>
                    </tr>
                    <tr>
                        <td>Consumo Calculado</td>
                        <td></td>
                        <td class="text-end">{{ $reading->cm3 }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <div class="bg-primary text-white p-1 text-center fw-bold">
                    Su detalle de consumo en Pesos ($)
                </div>
                <table class="table table-sm table-bordered mb-0">
                    <tr>
                        <td>(+) Cargo Fijo</td>
                        <td class="text-end">@money($configCost->fixed_charge_penalty)</td>
                    </tr>
                    <tr>
                        <td>(+) Consumo Agua Potable</td>
                        <td class="text-end">@money($reading->vc_water)</td>
                    </tr>
                    <tr>
                        <td>(-) Subsidio  @if ($reading->service->percentage && $reading->service->percentage != 0  )
                         {{ $reading->service->percentage }}%
                        @else
                    0%
                    @endif
                      Tope {{ $configCost->max_covered_m3 }} M3</td>
                        <td class="text-end">
                            @money(isset($reading->v_subs) && is_numeric($reading->v_subs) ? $reading->v_subs : 0, 'CLP')
                        </td>
                    </tr>
                    <tr>
                        <td>(=) SubTotal Consumo Mes</td>
                        <td class="text-end">@money($reading->total_mounth)</td>
                    </tr>
                    <tr>
                        <td>(+) Saldo Anterior</td>
                        <td class="text-end">
                            @money($reading->s_previous)
                        </td>
                    </tr>
                    <tr>
                        <td>(+) Multas Vencidas</td>
                        <td class="text-end">
                            @money($reading->multas_vencidas)
                        </td>
                    </tr>
                    <tr>
                        <td>(+) Otros Cargos</td>
                        <td class="text-end">
                            @money($reading->other)
                        </td>
                    </tr>
                    <tr>
                        <td>(+) Corte y Reposición</td>
                        <td class="text-end">
                            @money($reading->corte_reposicion)
                        </td>
                    </tr>

                </table>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-8">
                <table class="table table-sm table-bordered mb-0 bg-light">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th class="text-end">TRAMOS</th>
                            <th class="text-end">M3</th>
                            <th class="text-end">$</th>
                            <th class="text-end">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tier as $tierConfig)
                            <tr>
                                <td class="text-end">{{ $tierConfig->range_from }} Hasta {{ $tierConfig->range_to }}</td>
                                <td class="text-end">{{ $tierConfig->m3 }} X</td>
                                <td class="text-end">@money($tierConfig->precio)</td>
                                <td class="text-end">@money($tierConfig->total)</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td class="text-end">Facturado</td>
                            <td class="text-end">{{ $reading->cm3 }}</td>
                            <td></td>
                            <td class="text-end">@money($tier->sum('total'))</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row justify-content-end">
            <div class="col-md-6">
                <table class="table table-sm table-bordered">
                   <tr>
                        <td class="fw-bold text-end">SubTotal</td>
                        <td class="text-end">@money($total_con_iva ?? $reading->total ?? 0)</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-end">Total</td>
                        <td class="text-end text-success fw-bold">@money($total_con_iva ?? $reading->total ?? 0)</td>
                    </tr>
                </table>
            </div>
        </div>



        <!--Gráfico de consumo -->
        <div class="row mb-3 position-relative" style="height: 180px;">
            <div class="col-12">
                 <img src="data:image/png;base64, {{$reading->invoice_bell}}" alt="Lectura {{$reading->id}} de {{$reading->invoice_type}}" />
                <div id="consumption-chart" style="height: 150px;"></div>
            </div>
        </div>



        <!-- Información de pago -->
        <div class="row mt-2">
            <div class="col-12 text-center">
                <p class="small mb-0">Último Pago {{ \Carbon\Carbon::now()->subDays(30)->format('d-m-Y') }} Monto {{ number_format($reading->total, 0, ',', '.') }}</p>
                <p class="small mb-0">Res.SII N° 99 de 2014</p>
                <p class="small mb-0">Verifique su documento: www.sii.cl</p>
                <p class="fw-bold text-success">El Agua es Vida, Cuídala...</p>
            </div>
        </div>

        <!-- Horario de oficina -->
        <div class="row mt-2">
            <div class="col-12">
                <div class="bg-info text-center p-1 small">
                    Horario de Atención: Lunes a Viernes desde las 14:00 a 18:00 horas HORARIO Verano
                </div>
            </div>
        </div>

        <!-- Botón Imprimir -->
        <div class="text-center mt-3 d-print-none">
            <button onclick="window.print()" class="btn btn-success"><i class="bi bi-printer"></i> Imprimir</button>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {

    const ctx = document.getElementById('consumption-chart');

});
</script>

<style>
@media print {
    @page {
        size: A4;
        margin: 0.5cm;
    }

    body {
        margin: 0;
        padding: 0;
    }

    body * {
        visibility: hidden;
    }

    .container, .container * {
        visibility: visible;
    }

    .container {
        width: 100%;
        position: absolute;
        left: 0;
        top: 0;
        margin: 0;
        padding: 0;
    }

    .d-print-none {
        display: none !important;
    }

    header, footer, nav, .navbar, .sidebar, .btn, button {
        display: none !important;
    }
}
</style>
@endsection
