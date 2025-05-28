@extends('layouts.nice', ['active'=>'services.create','title'=>'Agregar Servicio'])

@section('content')
<div class="pagetitle">
  <h1>Agregar Servicio a {{$member->first_name}} {{$member->last_name}}</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Inicio</a></li>
      <li class="breadcrumb-item"><a href="{{ route('orgs.members.index', $org->id) }}">Clientes</a></li>
      <li class="breadcrumb-item active">{{$member->first_name}} {{$member->last_name}}</li>
      <li class="breadcrumb-item active">Agregar Servicio</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="card">
    <div class="card-body">
      <form action="{{ route('orgs.services.store', [$org->id, $member->id]) }}"  method="POST">
        @csrf
        <div class="row m-3">
          <h5 class="text-primary mb-3">Datos del Servicio</h5>
            <!-- Mostrar errores -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


          <div class="mb-3 col-md-6">
            <div class="mb-3">
                <label for="locality_id" class="form-label">Ubicación</label> <!-- Cambié "Sector" a "Ubicación" -->
                <select class="form-select" id="locality_id" name="locality_id">
                    <option value="">Seleccione la ubicación</option>
                    @foreach($locations as $locaty)
                    <option value="{{$locaty->id}}">{{$locaty->name}}</option>
                    @endforeach
                </select>
            </div>
          </div>

          <div class="mb-3 col-md-6">
            <label for="meter_plan" class="form-label">MIDEPLAN</label>
            <select class="form-select" id="meter_plan" name="meter_plan">
              <option value="">Seleccione</option>
              <option value="1">Sí</option>
              <option value="0">No</option>
            </select>
          </div>

          <div class="mb-3 col-md-6">
            <label for="percentage" class="form-label">Porcentaje</label>
            <div class="input-group">
              <input type="number" class="form-control" name="percentage" id="percentage" min="0" max="100">
              <span class="input-group-text">%</span>
            </div>
          </div>

          <div class="mb-3 col-md-6">
            <label for="meter_type" class="form-label">Tipo Medidor</label>
            <select class="form-select" id="meter_type" name="meter_type">
              <option value="">Seleccione</option>
              <option value="analogico">Análogo</option>
              <option value="digital">Digital</option>
            </select>
          </div>

          <div class="mb-3 col-md-6">
            <label for="meter_number" class="form-label">N° Medidor</label>
            <input type="text" class="form-control" id="meter_number" name="meter_number" required>
          </div>

          <div class="mb-3 col-md-6">
            <label for="invoice_type" class="form-label">Boleta/Factura</label>
            <select class="form-select" id="invoice_type" name="invoice_type" required>
              <option value="">Seleccione</option>
              <option value="boleta">Boleta Exenta</option>
              <option value="factura">Factura</option>
            </select>
          </div>

          <div class="mb-3 col-md-6">
            <label for="diameter" class="form-label">Diámetro de Conexión</label>
            <select class="form-select" id="diameter" name="diameter" required>
              <option value="">Seleccione</option>
              <option value="1/2">1/2</option>
              <option value="3/4">3/4</option>
            </select>
          </div>


          <div class="mb-3 col-md-6">
            <label for="cliente_socio" class="form-label">Cliente/Socio</label>
            <select class="form-control" name="partner">
                    <option value="cliente" {{ old('partner') == 'cliente' ? 'selected' : '' }}>Cliente</option>
                    <option value="socio" {{ old('partner') == 'socio' ? 'selected' : '' }}>Socio</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="observations" class="form-label">Observaciones</label>
            <textarea class="form-control" id="observations" name="observations" rows="3" required></textarea>
          </div>

          <div class="d-grid gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Guardar Servicio</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const meterPlanSelect = document.getElementById('meter_plan');
    const percentageLabel = document.querySelector('label[for="percentage"]');  // Seleccionamos la etiqueta de porcentaje
    const percentageInput = document.getElementById('percentage');

    function togglePercentageField() {
        if (meterPlanSelect.value === '0') {
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
        const isMideplanEnabled = meterPlanSelect.value === '1';

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
