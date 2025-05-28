@extends('layouts.nice', ['active' => 'orgs.members.create', 'title' => 'Crear Socio'])

@section('content')
    <div class="pagetitle">
        <h1>{{$org->name}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.dashboard', $org->id)}}">{{$org->name}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.members.index', $org->id)}}">Socios</a></li>
                <li class="breadcrumb-item active">Crear Socio</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form id="newMemberForm" action="{{ route('orgs.members.store', $org->id) }}" method="POST">
                    @csrf
                    <div class="row m-3">
                        <!-- Columna izquierda - Datos personales -->
                        <div class="col-md-6 border-end pe-md-4">
                            <h5 class="text-primary mb-3">Datos Personales</h5>
                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label for="rut" class="form-label">RUT</label>
                                    <input type="text" class="form-control @error('rut') is-invalid @enderror" id="rut"
                                        name="rut" value="{{ old('rut') }}" required maxlength="10" oninput="validarInputRut(this)">
                                    @error('rut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="rut-error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="partner" class="form-label">Cliente/Socio</label>
                                    <select class="form-select" id="partner" name="partner">
                                        <option value="">Seleccionar...</option>
                                        <option value="cliente" {{ old('partner') == 'C' ? 'selected' : '' }}>Cliente</option>
                                        <option value="socio" {{ old('partner') == 'S' ? 'selected' : '' }}>Socio</option>
                                    </select>
                                </div>

                                <div class="mb-3 row">
                                    <div class="col-md-6">
                                        <label for="nombres" class="form-label">Nombres</label>
                                        <input type="text" class="form-control" id="nombres" name="first_name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="apellidos" class="form-label">Apellidos</label>
                                        <input type="text" class="form-control" id="apellidos" name="last_name" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="gender" class="form-label">Género</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="">Seleccionar...</option>
                                        <option value="masculino" {{ old('gender') == 'MASCULINO' ? 'selected' : '' }}>
                                            Masculino</option>
                                        <option value="femenino" {{ old('gender') == 'FEMENINO' ? 'selected' : '' }}>Femenino
                                        </option>
                                        <option value="otro" {{ old('gender') == 'OTRO' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo electrónico</label>
                                    <input type="email" class="form-control" id="email" value="{{ old('email') }}"
                                        name="email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                                        title="Por favor ingrese un correo electrónico válido">
                                </div>
                                <div class="mb-3 row">
                                    <div class="col-md-4">
                                        <label for="state" class="form-label">Región</label>
                                        <select class="form-select" id="state" name="state">
                                            <option value="">Seleccionar Región</option>
                                            @foreach($states as $state)
                                                <option value="{{$state->id}}" {{ old('state') == $state->id ? 'selected' : '' }}>
                                                    {{$state->name_state}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="province" class="form-label">Provincia</label>
                                        <select class="form-select" id="province" name="province">
                                            <option value="">Seleccionar Provincia</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="city" class="form-label">Comuna</label>
                                        <select class="form-select" id="city" name="city">
                                            <option value="">Seleccionar Comuna</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="address" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label for="celular" class="form-label">Celular</label>
                                    <input type="tel" class="form-control" id="celular" name="mobile_phone">
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label">Teléfono fijo</label>
                                    <input type="tel" class="form-control" id="telefono" name="phone">
                                </div>
                            </div>

                        </div>

                        <!-- Columna derecha - Servicios -->
                        <div class="col-md-6 ps-md-4">
                            <h5 class="text-primary mb-3">Información de Servicios <span
                                    class="text-muted fs-6 fw-normal"></span></h5>
                            <div class="mb-3">
                                <label for="locality_id" class="form-label">Sector</label>
                                <select class="form-select" id="locality_id" name="locality_id">
                                    <option value="">Seleccione el sector</option>
                                    @foreach($locations as $location)
                                        <option value="{{$location->id}}">{{$location->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="meter_plan" class="form-label">MIDEPLAN</label>
                                    <select class="form-select" id="meter_plan" name="meter_plan">
                                        <option value="">Seleccionar</option>
                                        <option value="1" {{ old('meter_plan') == '1' ? 'selected' : '' }}>Sí</option>
                                        <option value="0" {{ old('meter_plan') == '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="percentage" class="form-label">Porcentaje</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="percentage" name="percentage" min="0"
                                            max="100" value="{{ old('percentage') }}">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="meter_type" class="form-label">Tipo Medidor</label>
                                    <select class="form-select" id="meter_type" name="meter_type">
                                        <option value="">Seleccione el medidor</option>
                                        <option value="analogico" {{ old('meter_type') == 'analogico' ? 'selected' : '' }}>
                                            Análogo</option>
                                        <option value="digital" {{ old('meter_type') == 'digital' ? 'selected' : '' }}>Digital
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="meter_number" class="form-label">N° Medidor</label>
                                    <input type="text" class="form-control" id="meter_number" name="meter_number"
                                        value="{{ old('meter_number') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="invoice_type" class="form-label">Boleta/Factura</label>
                                    <select class="form-select" id="invoice_type" name="invoice_type">
                                        <option value="">Seleccione Boleta/Factura</option>
                                        <option value="boleta" {{ old('invoice_type') == 'boleta' ? 'selected' : '' }}>Boleta
                                            Exenta</option>
                                        <option value="factura" {{ old('invoice_type') == 'factura' ? 'selected' : '' }}>
                                            Factura</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="diameter" class="form-label">Diámetro de Conexión</label>
                                    <select class="form-select" id="diameter" name="diameter">
                                        <option value="">Seleccione el Diámetro</option>
                                        <option value="1/2" {{ old('diameter') == '1/2' ? 'selected' : '' }}>1/2</option>
                                        <option value="3/4" {{ old('diameter') == '3/4' ? 'selected' : '' }}>3/4</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="observations" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observations" name="observations"
                                    rows="3">{{ old('observations') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Checkbox para "Activo" -->
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="activo" name="activo" {{ old('activo', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="activo">Dejar <b>Activo</b> si es nuevo cliente y no tiene
                            servicios asignados</label>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Añadir cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const activoCheckbox = document.getElementById('activo');
            const serviceSection = document.querySelector('.col-md-6.ps-md-4');

            function toggleServiceFields() {
                serviceSection.style.display = activoCheckbox.checked ? 'block' : 'none';
            }

            activoCheckbox.addEventListener('change', toggleServiceFields);
            toggleServiceFields();

            // Función para manejar el bloqueo del campo de porcentaje según MIDEPLAN
            const mideplanSelect = document.getElementById('meter_plan');
            const porcentajeInput = document.getElementById('percentage');

            function togglePercentageField() {
                if (mideplanSelect.value === '0') {
                    porcentajeInput.disabled = true; // Deshabilitar el campo de porcentaje
                    porcentajeInput.value = ''; // Limpiar el valor si está deshabilitado
                } else {
                    porcentajeInput.disabled = false; // Habilitar el campo de porcentaje
                }
            }

            mideplanSelect.addEventListener('change', togglePercentageField);
            togglePercentageField(); // Inicializar el estado del campo porcentaje
        });


        $(function () {
            $("#state").change(function (e) {
                var id = "";
                if ($("#state").val() != null) {
                    id = $("#state").val();
                }
                $.ajax({
                    url: "/ajax/" + id + "/provincias",
                    type: "get",
                    dataType: "json",
                    data: {
                        'id': id,
                    },
                    success: function (resultado) {
                        //alert(resultado);
                        $('#province').empty();
                        $('#province').append('<option value="">Seleccionar Provincia</option>');
                        resultado.forEach(function (key, index) {
                            $('#province').append('<option value="' + key.id + '">' + key.name_province + '</option>');
                        });
                    }
                });
            });
        });

        $(function () {
            $("#province").change(function (e) {
                var id = "";
                if ($("#province").val() != null) {
                    id = $("#province").val();
                }
                $.ajax({
                    url: "/ajax/" + id + "/comunas",
                    type: "get",
                    dataType: "json",
                    data: {
                        'id': id,
                    },
                    success: function (resultado) {
                        //alert(resultado);
                        $('#city').empty();
                        $('#city').append('<option value="">Seleccionar Comuna</option>');
                        resultado.forEach(function (key, index) {
                            $('#city').append('<option value="' + key.id + '">' + key.name_city + '</option>');
                        });
                    }
                });
            });
        });
        $(document).ready(function () {
            $('#rut').on('blur', function () {
                const rut = $(this).val();
                if (rut) {
                    $.ajax({
                        url: '/ajax/check-rut',
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'rut': rut
                        },
                        success: function (response) {
                            if (response.exists) {
                                $('#rut').addClass('is-invalid');
                                $('#rut-error').text('Este RUT ya está registrado.');
                            } else {
                                $('#rut').removeClass('is-invalid');
                                $('#rut-error').text('');
                            }
                        }
                    });
                }
            });
        });








        function validarInputRut(input) {
        // Solo permite números, guion y K/k
        input.value = input.value.replace(/[^0-9kK\-]/g, '');

        // Opcional: autoformatear RUT, evitar múltiples guiones, etc.
        const rut = input.value;

        // Validar si la longitud mínima es aceptable antes de validar RUT completo
        if (rut.length >= 8) {
            if (!validarRut(rut)) {
                document.getElementById('rut-error').innerText = 'RUT inválido.';
                input.classList.add('is-invalid');
            } else {
                document.getElementById('rut-error').innerText = '';
                input.classList.remove('is-invalid');
            }
        } else {
            document.getElementById('rut-error').innerText = '';
            input.classList.remove('is-invalid');
        }
    }

    function validarRut(rut) {
        // Limpia el RUT
        rut = rut.replace(/[^0-9kK]/g, '').toUpperCase();

        if (rut.length < 2) return false;

        const cuerpo = rut.slice(0, -1);
        const dv = rut.slice(-1);

        let suma = 0;
        let multiplo = 2;

        for (let i = cuerpo.length - 1; i >= 0; i--) {
            suma += parseInt(cuerpo.charAt(i)) * multiplo;
            multiplo = multiplo < 7 ? multiplo + 1 : 2;
        }

        const dvEsperado = 11 - (suma % 11);
        let dvCalculado = '';

        if (dvEsperado === 11) {
            dvCalculado = '0';
        } else if (dvEsperado === 10) {
            dvCalculado = 'K';
        } else {
            dvCalculado = dvEsperado.toString();
        }

        return dv === dvCalculado;
    }

    </script>
@endsection
