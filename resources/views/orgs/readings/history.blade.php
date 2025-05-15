@extends('layouts.nice', ['active'=>'orgs.readings.histories','title'=>'Historial de lecturas y consumos'])

@section('content')
    <div class="pagetitle">
      <h1>{{$org->name}}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
          <li class="breadcrumb-item active">Lecturas</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

<section class="section dashboard">
    <div class="card top-selling overflow-auto">
        <div class="card-body pt-2">
                <form method="GET" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <!-- Rango de Fecha (Desde) -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Desde</label>
                            <input type="date" name="start_date" class="form-control rounded-3" value="{{ request('start_date') }}">
                        </div>
                        <!-- Rango de Fecha (Hasta) -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Hasta</label>
                            <input type="date" name="end_date" class="form-control rounded-3" value="{{ request('end_date') }}">
                        </div>
                        <!-- Sectores -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Sectores</label>
                            <select name="sector" class="form-select rounded-3">
                                @if($locations->isNotEmpty())
                                    <option value="">Todos</option>
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
                            <label class="form-label fw-semibold">Buscar por nombre o RUT</label>
                            <div class="input-group input-group-sm search-input-group">
                                <span class="input-group-text border-0 bg-white ps-4">
                                    <i class="bi bi-search fs-5 text-primary"></i>
                                </span>
                                <input type="text" id="search" name="search" class="form-control rounded-end-3" placeholder="Buscar por nombre, apellido o RUT" value="{{ request('search') }}">
                            </div>
                            <ul id="search-results" class="list-group position-absolute" style="width: 100%; display: none; z-index: 1000;">
                                <!-- Los resultados de la búsqueda se insertarán aquí -->
                            </ul>
                        </div>
                        
                        <!-- Botón Filtrar -->
                        <div class="col-md-2 d-flex">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill pulse-btn">
                                <i class="bi bi-funnel-fill me-2"></i>Filtrar
                            </button>
                        </div>
                        <div class="col-md-2 d-flex">
                            <a href="{{ route('orgs.readings.history.export', $org->id) }}" class="btn btn-primary w-100 rounded-pill pulse-btn">
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
                            <th scope="col">Lec.</th>
                            <th scope="col">Periodo</th>
                            <th scope="col">Sector</th>
                            <th scope="col">nro Servicio</th>
                            <th scope="col">RUT/RUN</th>
                            <th scope="col">Nombre/Apellido</th>
                            <th scope="col">Fecha Reg.</th>
                            <th scope="col" class="text-center">Lectura</th>
                            <th scope="col" class="text-center">Consumo Mes</th>
                            <th scope="col">Monto Mes</th>
                            <th scope="col">Documento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($readings as $reading)
                        <tr data-id="{{ $reading->id }}">
                            <td>{{ $reading->id }}</td>
                            <td>{{ $reading->period }}</td>
                            <td>{{ $reading->location_name ?? 'N/A' }}</td>
                            <td>{{ Illuminate\Support\Str::padLeft($reading->nro,5,0) }}</td>
                            <td class="text-end"><a href="{{route('orgs.members.edit',[$org->id,$reading->member_id])}}">{{ $reading->rut }}</a></td>
                            <td>{{ $reading->full_name ?? 'N/A'}}</td>
                            <td>{{ $reading->period }}</td>
                            <td class="text-center">{{ $reading->current_reading }}</td>
                            <td class="text-end"><span class="text-primary fw-bold">{{ $reading->cm3 }}</span></td>
                            <td class="text-end"><span class="text-success fw-bold">@money($reading->total)</span></td>
                            <td class="text-center">
                                @if($reading->invoice_type="boleta")
                                <a href="{{ route('orgs.readings.boleta', [$reading->org_id,  $reading->id]) }}" class="btn btn-info btn-sm" target="_black"><i class="bi bi-file-earmark-post"></i> Ver Boleta</a>
                                @else
                                <a href="{{ route('orgs.readings.factura', [$reading->org_id, $reading->id]) }}" class="btn btn-warning btn-sm" target="_black"><i class="bi bi-file-earmark"></i> Ver Factura</a>
                                @endif
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
                                        <form action="{{ route('orgs.readings.update', [$org->id]) }}" method="POST" id="editReadingForm">
                                            @csrf
                                            <input type="hidden" name="reading_id" id="reading_id">
                                            <div class="mb-3">
                                                <label for="current_reading" class="form-label">Lectura Actual:</label>
                                                <input type="number" class="form-control" id="current_reading"  name="current_reading"  required>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary" id="saveReadingBtn" onclick="document.getElementById('editReadingForm').submit();">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                              
                          
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">{!! $readings->render('pagination::bootstrap-4') !!}</div>
    </div>
</section>

@endsection


@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {

    $('#editReadingModal').on('show.bs.modal', function (event) {
        
        var button = $(event.relatedTarget);
        
        
        var readingId = button.data('bs-id');
        var currentReading = button.data('bs-current');
        
       
        var modal = $(this);
        
        
        modal.find('#reading_id').val(readingId);
        modal.find('#current_reading').val(currentReading);
        
        
        
        
    });
});

function openModal(readingId, currentReading) {
    document.getElementById('reading_id').value = readingId;
    document.getElementById('current_reading').value = currentReading;
    console.log(readingId);
    console.log(currentReading);
    $('#editReadingModal').modal('show');
}

</script>
@endsection
