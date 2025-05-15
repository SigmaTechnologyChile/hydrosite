@extends('layouts.nice', ['active'=>'orgs.payments.index','title'=>'Pagos'])

@section('content')
    <div class="pagetitle">
      <h1>{{$org->name}}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
          <li class="breadcrumb-item active">Pagos</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->


    <section class="section dashboard">
        <div class="card top-selling overflow-auto">
            <div class="card-body pt-2">
                <form method="GET" id="filterForm">
                        <div class="row g-3 align-items-end">
                            <!-- Sectores -->
                            <div class="col-md-3">
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
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Buscar</label>
                                <div class="input-group input-group-sm search-input-group">
                                    <span class="input-group-text border-0 bg-white ps-4">
                                        <i class="bi bi-search fs-5 text-primary"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control rounded-end-3" placeholder="Buscar por nombre, apellido, Rut" value="{{ request('search') }}">
                                </div>
                            </div>
    
                            <!-- Botón Filtrar -->
                            <div class="col-md-2 d-flex">
                                <button type="submit" class="btn btn-primary w-100 rounded-pill pulse-btn">
                                    <i class="bi bi-funnel-fill me-2"></i>Filtrar
                                </button>
                            </div>
                            <div class="col-md-2 d-flex">
                                <a href="{{route('orgs.payments.export',$org->id)}}" class="btn btn-primary w-100 rounded-pill pulse-btn">
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
                                <th scope="col">N° Cliente</th>
                                <th scope="col">RUT/RUN</th>
                                <th scope="col">Nombres</th>
                                <th scope="col">Apellidos</th>
                                <th scope="col">Servicios</th>
                                <th scope="col">Estado</th>
                                <th scope="col">TOTAL ($)</th>
                                <th scope="col" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                            <tr data-id="{{ $member->id }}">
                                <td>{{ $member->id }}</td>
                                <td><a href="{{route('orgs.members.edit',[$org->id,$member->id])}}">{{ $member->rut }}</a></td>
                                <td>{{ $member->first_name }}</td>
                                <td>{{ $member->last_name }}</td>
                                <td>{{ $member->qrx_serv }}</td>
                                <td><span class="badge bg-warning ">Pendiente de pago</span></td>
                                <td class="text-end">
                                    <span class="text-success fw-bold">@money($member->total_amount)</span>
                                <td class="text-center">
                                   <!-- Aquí creamos el botón para ver los servicios -->
                                    <a href="{{route('orgs.payments.services',[$org->id,$member->rut])}}" class="btn btn-sm btn-success">Ver servicios</a>
                                </td>
                            </tr>
                            @endforeach
                            <!-- Modal de Edición de Lectura -->
                            <div class="modal fade" id="editReadingModal" tabindex="-1" aria-labelledby="editReadingModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="editReadingModalLabel">Editar Lectura</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <form id="editReadingForm" method="POST">
                                      @csrf
                                      @method('PUT') 
                                      <div class="mb-3">
                                        <label for="current_reading" class="form-label">Lectura Actual</label>
                                        <input type="number" class="form-control" id="current_reading" name="current_reading" required>
                                      </div>
                                      <input type="hidden" id="reading_id" name="reading_id">
                                    </form>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" form="editReadingForm" class="btn btn-primary">Guardar</button>
                                  </div>
                                </div>
                              </div>
                            </div>
    
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">{!! $members->render('pagination::bootstrap-4') !!}</div>
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