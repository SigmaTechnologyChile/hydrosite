@extends('layouts.nice', ['active' => 'readings.factura', 'title' => 'Factura Exenta Electrónica'])

@section('content')
<div class="container py-3" style="max-width: 800px;">
    <div class="border p-3 rounded shadow bg-white">
        <div class="row">
            <!-- Información del Comité -->
            <div class="col-md-8">
                <div class="d-flex align-items-start mb-2">
                    <img src="{{ $org->logo }}" alt="Logo" width="150" height="150" class="me-3">
                    <div>
                        <h5 class="fw-bold text-primary mb-0">COMITÉ AGUA POTABLE RURAL LAS ARBOLEDAS</h5>
                        <p class="mb-0 small">Callejón La Caja S/N, Las Arboledas - Teno</p>
                        <p class="mb-0 small">Correo: comiteaprlasarboledas@gmail.com</p>
                        <p class="mb-0 small">Cel: +56 9 5858 6377</p>
                        <p class="mb-0 small">Emergencias: +56 9 8646 8372</p>
                    </div>
                </div>
            </div>

            <!-- Factura Exenta -->
            <div class="col-md-4 text-end">
                <div class="border border-danger p-2 text-center">
                    <p class="mb-1 fw-bold">RUT: 71.108.700-1</p>
                    <p class="mb-1 text-danger fw-bold">FACTURA ELECTRÓNICA</p>
                    <p class="mb-1 fw-bold">N° {{ $reading->folio }}</p>
                    <p class="mb-1">S.I.I. CURICÓ</p>
                    <p class="mb-0">Fecha: {{ \Carbon\Carbon::parse($reading->fecha_lectura)->format('d/m/Y') }}</p>
                </div>
            </div>
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
                        <td>{{ $reading->service->mideplan ?? '0' }}</td>
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
                        <td class="text-end">{{ $reading->consumo }}</td>
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
                        <td class="text-end">@money($org->fixed_charge)</td>
                    </tr>
                    <tr>
                        <td>(+) Consumo Agua Potable</td>
                        <td class="text-end">@money($reading->vc_water)</td>
                    </tr>
                    <tr>
                        <td>(+) Subsidio 50% Tope 13 M3</td>
                        <td class="text-end">
                            @money(isset($reading->subsidy) && is_numeric($reading->subsidy) ? $reading->subsidy : 0, 'CLP')
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
                        @foreach($sections as $section)
                            <tr>
                                <td class="text-end">{{ $section->from_to }} Hasta {{ $section->until_to }}</td>
                                <td class="text-end">{{ $section->m3 }} X</td>
                                <td class="text-end">@money($section->cost)</td>
                                <td class="text-end">@money($section->total)</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td class="text-end">Facturado</td>
                            <td class="text-end">{{ $reading->cm3 }}</td>
                            <td></td>
                            <td class="text-end">@money($sections->sum('total'))</td>
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
                        <td class="text-end">@money($reading->sub_total)</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-end">IVA (19%)</td>
                        <td class="text-end">@money($reading->tax)</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-end">Total IVA incluido</td>
                        <td class="text-end text-success fw-bold">@money($reading->total)</td>
                    </tr>
                </table>
            </div>
        </div>





        <!-- Gráfico de consumo -->
        <div class="row mb-3">
            <div class="col-12 text-center">
                <img src="data:image/png;base64, {{$reading->invoice_bell}}" alt="Gráfico consumo" style="max-width: 100%; height: auto;">
            </div>
        </div>

        <!-- Pie de página -->
        <div class="row">
            <div class="col-12 text-center small text-muted">
                <p class="mb-1">Resolución SII N° 99 / 2014 - Verifique este documento en www.sii.cl</p>
                <p class="mb-1">Gracias por su pago oportuno</p>
                <p class="fw-bold text-primary">¡El agua es vida, cuídala!</p>
            </div>
        </div>
        <!-- Botón Imprimir -->
        <div class="text-center mt-3 d-print-none">
            <button onclick="window.print()" class="btn btn-success"><i class="bi bi-printer"></i> Imprimir</button>
        </div>
    </div>
</div>
@endsection
