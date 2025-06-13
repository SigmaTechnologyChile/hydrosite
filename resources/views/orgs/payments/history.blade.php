@extends('layouts.nice', ['active'=>'orgs.payments.histories','title'=>'Historial de Pagos'])

@section('content')
    <div class="pagetitle">
      <h1>{{$org->name}}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
          <li class="breadcrumb-item active">Historial de Pagos</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card top-selling overflow-auto">
<div class="card-body pt-2">
                <form method="GET" id="filterForm" action="{{ route('orgs.payments.history', $org->id) }}">
                        <div class="row g-3 align-items-end">
                            <!-- Fecha Desde -->
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Fecha Desde</label>
                                <input type="date" name="start_date" class="form-control rounded-3" value="{{ request('start_date', date('Y-m-01')) }}">
                            </div>

                            <!-- Fecha Hasta -->
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Fecha Hasta</label>
                                <input type="date" name="end_date" class="form-control rounded-3" value="{{ request('end_date', date('Y-m-d')) }}">
                            </div>

                            <!-- Sectores -->
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Sectores</label>
                                <select name="sector" class="form-select rounded-3">
                                    @if($locations->isNotEmpty())
                                        <option value="">
                                            Todos
                                        </option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}" {{ old('sector', request()->sector) == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">No hay sectores disponibles</option>
                                    @endif
                                </select>
                            </div>

                            <!-- Buscador -->
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Buscar</label>
                                <div class="input-group input-group-sm search-input-group">
                                    <span class="input-group-text border-0 bg-white ps-4">
                                        <i class="bi bi-search fs-5 text-primary"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control rounded-end-3" placeholder="Buscar por nombre, apellido, sector" value="{{ request('search') }}">
                                </div>
                            </div>

                            <!-- Botón Filtrar -->
                            <div class="col-md-2 d-flex">
                                <button type="submit" class="btn btn-primary w-100 rounded-pill pulse-btn">
                                    <i class="bi bi-funnel-fill me-2"></i>Filtrar
                                </button>
                            </div>
                            <div class="col-md-2 d-flex">
                                <a href="{{ route('orgs.payments.history.export', $org->id) }}" class="btn btn-primary w-100 rounded-pill pulse-btn">
                                    <i class="bi bi-box-arrow-right me-2"></i>Exportar
                                </a>
                            </div>
                        </div>
                    </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Orden</th>
                                <th scope="col">Rut</th>
                                <th scope="col">Nombres</th>
                                <th scope="col">Apellidos</th>
                                <th scope="col">N° Servicio.</th>
                                <th scope="col">Sector</th>
                                <th scope="col">Folio</th>
                                <th scope="col">Tipo Dcto.</th>
                                <th scope="col">Monto</th>
                                <th scope="col">Estado de Pago</th>
                                <th scope="col">Metodo de Pago</th>
                                <th scope="col">Periodo</th>
                                <th scope="col">Fecha de Pago</th>
                                <th scope="col" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order_items as $item)
                            <tr data-id="{{ $item->id }}">
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->order_code }}</td>
                                <td class="text-end"><a href="{{route('orgs.members.edit',[$org->id, $item->member->id])}}">{{ $item->member->rut }}</a></td>
                                <td>{{ $item->member->first_name }}</td>
                                <td>{{ $item->member->last_name }}</td>
                                <td>{{ Illuminate\Support\Str::padLeft($item->service->nro,5,0) }}</td>
                                <td>{{ $item->location->name?? 'N/A' }}</td>
                                <td>{{ $item->folio }}</td>
                                <td>{{ $item->type_dte }}</td>
                                <td class="text-end">
                                    <span class="text-success fw-bold">@money($item->total)</span>
                                </td>
                                <td>
                                    @if($item->payment_status)
                                        <span class="badge bg-success">Pagado</span>
                                    @else
                                        <span class="badge bg-warning ">Pendiente de pago</span>
                                    @endif
                                </td>
                                <td>{{ $item->payment_method->title }}</td>
                                <td>{{ $item->reading->period }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td class="text-center">
                                    @if($item->type_dte="boleta")
                                    <a href="{{ route('orgs.readings.boleta', [$item->org_id,  $item->reading_id]) }}" target="_black" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Ver Boleta"><i class="bi bi-file-earmark-post"></i></a>
                                    @else
                                    <a href="{{ route('orgs.readings.factura', [$item->org_id, $item->reading_id]) }}" target="_black" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Ver Factura"><i class="bi bi-file-earmark"></i></a>
                                    @endif
                                            <a href="#" class="btn btn-sm btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Enviar Mail"><i class="ri-mail-send-line"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">{!! $order_items->render('pagination::bootstrap-4') !!} </div>
        </div>
    </section>

    <!-- Modal para Nuevo Miembro -->
    <div class="modal fade" id="newMemberModal" tabindex="-1" aria-labelledby="newMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="newMemberModalLabel">Nuevo Inventario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="newMemberForm" action="" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Columna izquierda - Datos personales -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Descripción del Artículo</label>
                                    <input type="text" class="form-control" id="description" name="description" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="qxt" class="form-label">Cantidad</label>
                                    <input type="number" class="form-control" id="qxt" name="qxt" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="order_date" class="form-label">Fecha último Pedido</label>
                                    <input type="date" class="form-control" id="order_date" name="order_date" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Valor</label>
                                    <input type="number" class="form-control" id="amount" name="amount" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Estado</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="Disponible">Disponible</option>
                                        <option value="En uso">En uso</option>
                                        <option value="En mantenimiento">En mantenimiento</option>
                                        <option value="Dado de baja">Dado de baja</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Ubicación</label>
                                    <input type="text" class="form-control" id="location" name="location">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="responsible" class="form-label">Nombre del responsable</label>
                                    <input type="text" class="form-control" id="responsible" name="responsible">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="low_date" class="form-label">Fecha de Traslado o Baja</label>
                                    <input type="date" class="form-control" id="low_date" name="low_date">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="observations" class="form-label">Observaciones (Opcional)</label>
                                    <textarea class="form-control" id="observations" name="observations" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">Añadir Inventario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
