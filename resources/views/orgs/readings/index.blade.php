@extends('layouts.nice', ['active'=>'orgs.readings.index','title'=>'Organizaciones'])

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
                        <!-- Año -->
                        <div class="col-md-1">
                            <label class="form-label fw-semibold">Año</label>
                            <select name="year" class="form-select rounded-3">
                                @for ($i = date('Y'); $i >= 2020; $i--)
                                    <option value="{{ $i }}" {{ request('year', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Mes -->
                        <div class="col-md-1">
                            <label class="form-label fw-semibold">Mes</label>
                            <select name="month" class="form-select rounded-3">
                                @foreach (['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $index => $month)
                                    <option value="{{ $index + 1 }}" {{ request('month', date('m')) == $index + 1 ? 'selected' : '' }}>{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sectores -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Sectores</label>
                            <select name="sector" class="form-select rounded-3">
                                @if($locations->isNotEmpty())
                                    <option value="">Todos</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('sector', request()->sector) == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                    @endforeach
                                @else
                                    <option value="">No hay sectores disponibles</option>
                                @endif
                            </select>
                        </div>

                        <!-- Buscador -->
                        <div class="col-md-3">
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
                            <a href="{{route('orgs.readings.export',$org->id)}}" class="btn btn-primary w-100 rounded-pill pulse-btn">
                                <i class="bi bi-box-arrow-right me-2"></i>Exportar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr class="table-light">
                                <th scope="col">ID Lec.</th>
                                <th scope="col">Sector</th>
                                <th scope="col">nro Servicio</th>
                                <th scope="col">RUT/RUN</th>
                                <th scope="col">Nombre/Apellido</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Lectura Anterior</th>
                                <th scope="col">Lectura Actual</th>
                                <th scope="col">Consumo <br/><small>(M³)</small></th>
                                <th scope="col">Total<br/><small>$</small></th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @foreach($readings as $reading)
                            <tr data-id="{{ $reading->id }}">
                                <td>{{ $reading->id }}</td>
                                <td>{{ $reading->location_name ?? 'N/A' }}</td>
                                <td>{{ Illuminate\Support\Str::padLeft($reading->nro,5,0) }}</td>
                                <td class="text-end"><a href="{{route('orgs.members.edit',[$org->id,$reading->member_id])}}">{{ $reading->rut }}</a></td>
                                <td>{{ $reading->full_name ?? 'N/A' }}</td>
                                <td>{{ $reading->period }}</td>
                                <td class="text-end"><span class="text-warning fw-bold">{{ $reading->previous_reading }}</span></td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('orgs.readings.current_reading_update', $org->id) }}"
                                          class="current-reading-form" data-reading-id="{{ $reading->id }}">
                                        @csrf
                                        <input type="hidden" name="reading_id" value="{{ $reading->id }}">
                                        <input type="number"
                                               name="current_reading"
                                               class="form-control form-control-sm current-reading-input"
                                               value="{{ $reading->current_reading }}"
                                               style="width: 80px; display: inline-block; text-align: right;"
                                               onkeydown="if(event.key==='Enter'){ this.form.submit(); }"
                                               data-row-index="{{ $loop->index }}">
                                    </form>
                                </td>
                                <td class="text-end"><span class="text-primary fw-bold">{{ $reading->cm3 }}</span></td>
                                <td class="text-end"><span class="text-danger fw-bold">= @money($reading->total)</span></td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end">
                                        <!-- Botón DTE -->
                                        @if($reading->invoice_type == "boleta")
                                            <a href="{{ route('orgs.readings.boleta', [$reading->org_id,  $reading->id]) }}" class="btn btn-dark btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Ver Boleta">
                                                <i class="ri-file-2-line"></i> DTE
                                            </a>
                                        @else
                                            <a href="{{ route('orgs.readings.factura', [$reading->org_id, $reading->id]) }}" class="btn btn-dark btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Ver Factura">
                                                <i class="ri-file-2-line"></i> DTE
                                            </a>
                                        @endif

                                        <!-- Botón Editar -->
                                        <button class="btn btn-sm btn-success edit-btn"
                                                data-bs-id="{{ $reading->id }}"
                                                data-bs-current="{{ $reading->current_reading }}"
                                                data-bs-previous="{{ $reading->previous_reading }}"
                                                data-bs-fines="{{ $reading->fines }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editReadingModal"
                                                data-bs-placement="top"
                                                title="Editar">
                                            <i class="ri-edit-box-line"></i> Editar
                                        </button>
                                    </div>
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
                                            <form method="POST" action="{{ route('orgs.readings.update', $org->id) }}" id="editReadingForm">
                                                @csrf
                                                @method('POST')

                                                <!-- Input para el ID de la lectura -->
                                                <input type="hidden" id="reading_id" name="reading_id">

                                                <div class="mb-3">
                                                    <label for="previous_reading" class="form-label">Lectura Anterior</label>
                                                    <input type="number" class="form-control" id="previous_reading" name="previous_reading" readonly>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="current_reading" class="form-label">Lectura Actual</label>
                                                    <input type="number" class="form-control" id="current_reading" name="current_reading" required>
                                                </div>

                                                <!-- Casillas de verificación -->
                                                <div class="mb-3">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" id="cargo_vencido" name="cargo_vencido">
                                                        <label class="form-check-label" for="cargo_vencido">
                                                            Cargo Vencido (@money($org->interest_due))
                                                        </label>
                                                    </div>

                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" id="cargo_mora" name="cargo_mora">
                                                        <label class="form-check-label" for="cargo_mora">
                                                            Cargo Mora (@money($org->interest_late))
                                                        </label>
                                                    </div>

                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" id="cargo_corte_reposicion" name="cargo_corte_reposicion">
                                                        <label class="form-check-label" for="cargo_corte_reposicion">
                                                            Cargo Corte Reposición (@money($org->replacement_cut))
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Multa -->
                                                <div class="mb-3">
                                                    <label for="other" class="form-label">Otros Cargos</label>
                                                    <input type="number" class="form-control" id="other" name="other" value="0">
                                                </div>

                                                <div class="mb-3">
                                                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                                                </div>
                                            </form>
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
    const currentReadingInputs = document.querySelectorAll('.current-reading-input');


    currentReadingInputs.forEach(input => {

        input.addEventListener('focus', function() {
            this.dataset.originalValue = this.value;
        });


        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();

                const form = this.closest('form');

                if (this.value !== this.dataset.originalValue) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', form.action, true);
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                    // Manejar la respuesta
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            console.log('Actualización exitosa');
                            // Puede mostrar un mensaje breve de éxito si lo desea
                        }
                    };

                    // Enviar el formulario
                    const formData = new FormData(form);
                    xhr.send(formData);
                }

                // Obtener el índice actual y buscar el siguiente input
                const currentIndex = parseInt(this.dataset.rowIndex);
                const nextIndex = currentIndex + 1;

                // Buscar el siguiente input si existe
                const nextInput = document.querySelector(`.current-reading-input[data-row-index="${nextIndex}"]`);
                if (nextInput) {
                    // Enfocar el siguiente input
                    nextInput.focus();
                    // Seleccionar todo el texto para facilitar la edición
                    nextInput.select();
                }
            }
        });

        // Restaurar valor original al perder el foco sin cambios
        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.value = this.dataset.originalValue;
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', function() {
    // Event listener para cuando el modal se muestre
    $('#editReadingModal').on('show.bs.modal', function (event) {

        // Obtener el botón que ha activado el modal
        var button = $(event.relatedTarget);

        // Extraer los datos del botón
        var readingId = button.data('bs-id');
        var currentReading = button.data('bs-current');
        var previousReading = button.data('bs-previous');
        var fines = button.data('bs-fines') || 0;

        // Guardar los valores en localStorage para usar al actualizar
        localStorage.setItem('readingId', readingId);
        localStorage.setItem('currentReading', currentReading);
        localStorage.setItem('previousReading', previousReading);
        localStorage.setItem('fines', fines);

        // Referenciar el modal
        var modal = $(this);

        // Asignar los valores a los campos del modal
        modal.find('#reading_id').val(readingId);
        modal.find('#current_reading').val(currentReading);
        modal.find('#previous_reading').val(previousReading);
        modal.find('#fines').val(fines);

        // Casillas de verificación para cargos
        modal.find('#cargo_mora').prop('checked', false);
        modal.find('#cargo_vencido').prop('checked', false);
        modal.find('#cargo_corte_reposicion').prop('checked', false);

        // Verificar si alguna multa o cargo es aplicable
        if (fines === 800) {
            modal.find('#cargo_vencido').prop('checked', true);
        }
    });

    // Añadir listeners para los checkboxes
    $('#cargo_vencido, #cargo_mora, #cargo_corte_reposicion').on('change', function() {
        var totalFines = 0;

        if ($('#cargo_mora').is(':checked')) {
            totalFines += 800;
        }

        if ($('#cargo_vencido').is(':checked')) {
            totalFines += 1600;
        }

        if ($('#cargo_corte_reposicion').is(':checked')) {
            totalFines += 10000;  // Actualizado a 10,000 para "Corte Reposición"
        }

        $('#fines').val(totalFines);
    });

    // Función para manejar los datos al actualizar el formulario
    $('#editReadingForm').on('submit', function() {
        // Los valores ya están en los campos del formulario gracias a los listeners anteriores
        // No necesitamos hacer nada especial aquí, los valores se enviarán correctamente

        // Limpiar el almacenamiento local
        localStorage.removeItem('readingId');
        localStorage.removeItem('currentReading');
        localStorage.removeItem('previousReading');
        localStorage.removeItem('fines');
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
