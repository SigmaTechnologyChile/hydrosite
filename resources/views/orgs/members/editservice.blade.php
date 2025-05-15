@extends('layouts.nice', ['active'=>'orgs.members.index','title'=>'Editar Servicio'])

@section('content')
<div class="pagetitle">
    <h1 class="text-primary">Editar Servicio</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orgs.members.index', $org->id) }}">Miembros</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orgs.members.edit', [$org->id, $member->id]) }}">{{ $member->first_name }} {{ $member->last_name }}</a></li>
            <li class="breadcrumb-item active">Editar Servicio</li>
        </ol>
    </nav>
</div>

<div class="card shadow">
    <div class="card-body">
        <!-- Formulario de Edición del Servicio -->
        <h5 class="card-title text-primary">Datos del Servicio</h5>

        <form action="{{ route('orgs.members.services.update', [$org->id, $member->id, $service->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-12">
                    <div class="p-3 border rounded shadow-sm bg-light">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nro" class="form-label">ID Servicio</label>
                                <input type="text" class="form-control bg-white @error('nro') is-invalid @enderror" id="nro" name="nro" value="{{ old('nro', $service->nro) }}" readonly>
                                @error('nro')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="sector" class="form-label">Sector</label>
                                <select class="form-select @error('sector') is-invalid @enderror" id="sector" name="sector">
                                    <option value="">Seleccione el sector</option>
                                    @foreach($sectors as $sector)  <!-- Reemplazamos el array estático por los sectores dinámicos -->
                                        <option value="{{ $sector->id }}" 
                                            {{ old('sector', $service->sector_id) == $sector->id ? 'selected' : '' }}>
                                            {{ $sector->name }}  <!-- Ajusta 'name' al campo adecuado de tu modelo Sector -->
                                        </option>
                                    @endforeach
                                </select>
                                @error('sector')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="meter_plan" class="form-label">MIDEPLAN</label>
                                <select class="form-select @error('meter_plan') is-invalid @enderror" id="meter_plan" name="meter_plan">
                                    <option value="">Seleccione</option>
                                    <option value="si" {{ old('meter_plan', $service->meter_plan) == 'si' ? 'selected' : '' }}>Sí</option>
                                    <option value="no" {{ old('meter_plan', $service->meter_plan) == 'no' ? 'selected' : '' }}>No</option>
                                </select>
                                @error('meter_plan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6" id="percentage-container">
                                <label for="percentage" class="form-label">Porcentaje</label>
                                <div class="input-group">
                                    <input type="number" name="percentage" id="percentage" step="0.01" class="form-control @error('percentage') is-invalid @enderror" value="{{ old('percentage', $service->percentage) }}">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('percentage')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="meter_type" class="form-label">Tipo Medidor</label>
                                <select class="form-select @error('meter_type') is-invalid @enderror" id="meter_type" name="meter_type">
                                    <option value="">Seleccione</option>
                                    <option value="analogico" {{ old('meter_type', $service->meter_type) == 'analogico' ? 'selected' : '' }}>Análogo</option>
                                    <option value="digital" {{ old('meter_type', $service->meter_type) == 'digital' ? 'selected' : '' }}>Digital</option>
                                </select>
                                @error('meter_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="meter_number" class="form-label">N° Medidor</label>
                                <input type="text" class="form-control @error('meter_number') is-invalid @enderror" id="meter_number" name="meter_number" value="{{ old('meter_number', $service->meter_number) }}" required>
                                @error('meter_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="invoice_type" class="form-label">Tipo de Documento</label>
                                <select class="form-select @error('invoice_type') is-invalid @enderror" id="invoice_type" name="invoice_type" required>
                                    <option value="">Seleccione</option>
                                    <option value="boleta" {{ old('invoice_type', $service->invoice_type) == 'boleta' ? 'selected' : '' }}>Boleta Exenta</option>
                                    <option value="factura" {{ old('invoice_type', $service->invoice_type) == 'factura' ? 'selected' : '' }}>Factura</option>
                                </select>
                                @error('invoice_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="diameter" class="form-label">Diámetro de Conexión</label>
                                <select class="form-select @error('diameter') is-invalid @enderror" id="diameter" name="diameter" required>
                                    <option value="">Seleccione</option>
                                    <option value="1/2" {{ old('diameter', $service->diameter) == '1/2' ? 'selected' : '' }}>1/2</option>
                                    <option value="3/4" {{ old('diameter', $service->diameter) == '3/4' ? 'selected' : '' }}>3/4</option>
                                </select>
                                @error('diameter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 text-center mt-4">
                                <label for="active" class="form-label d-block">¿Servicio Activo?</label>
                                
                                <div class="form-check form-switch d-inline-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" id="active" name="active" value="1" {{ old('active', $service->active) ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2" for="active">
                                        <span id="active-label">{{ old('active', $service->active) ? 'Sí' : 'No' }}</span>
                                    </label>
                                </div>
                                  
                                @error('active')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOTONES: CENTRADOS -->
            <div class="d-flex justify-content-center gap-2 mt-4">
                <button type="submit" class="btn btn-primary shadow">Guardar Cambios</button>
                <a href="{{ route('orgs.members.edit', [$org->id, $member->id]) }}" class="btn btn-outline-secondary shadow">Cancelar</a>
            </div>
        </form>

    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const meterPlanSelect = document.getElementById('meter_plan');
    const percentageLabel = document.querySelector('label[for="percentage"]');  // Seleccionamos la etiqueta de porcentaje
    const percentageInput = document.getElementById('percentage');
    
    function togglePercentageField() {
        if (meterPlanSelect.value === 'no') {
            percentageInput.disabled = true; // Deshabilitar el campo de porcentaje
            percentageInput.value = ''; // Limpiar el valor si está deshabilitado
        } else {
            percentageInput.disabled = false; // Habilitar el campo de porcentaje
        }
    }

    meterPlanSelect.addEventListener('change', togglePercentageField);
    togglePercentageField(); // Inicializar el estado del campo porcentaje
    
    function updatePercentageField() {
        // Obtener el contenedor actual
        const isMideplanEnabled = meterPlanSelect.value === 'si';
        
        // Reconstruir el campo según el estado de MIDEPLAN
        if (isMideplanEnabled) {
            // Campo habilitado
            percentageInput.setAttribute('step', '0.01');
            percentageInput.removeAttribute('readonly');
        } else {
            // Campo completamente deshabilitado
            percentageInput.setAttribute('readonly', true);
            percentageInput.setAttribute('step', '0');
        }
    }
    
    // Establecer el estado inicial
    updatePercentageField();
    
    // Escuchar cambios
    if (meterPlanSelect) {
        meterPlanSelect.addEventListener('change', updatePercentageField);
    }
    
    // Código para el checkbox de activo
    const activeCheckbox = document.getElementById('active');
    const activeLabel = document.getElementById('active-label');
    
    if (activeCheckbox && activeLabel) {
        activeCheckbox.addEventListener('change', function() {
            activeLabel.textContent = this.checked ? 'Sí' : 'No';
        });
    }
});
</script>
@endsection
