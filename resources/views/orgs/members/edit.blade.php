@extends('layouts.nice', ['active'=>'orgs.members.index','title'=>'Ver/Editar Miembro'])

@section('content')
    <div class="pagetitle">
        <h1>Detalles de Miembro: {{$member->first_name}} {{$member->last_name}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.dashboard', $org->id)}}">{{$org->name}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.members.index', $org->id)}}">Miembros</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.members.index', $org->id)}}">{{$member->first_name}} {{$member->last_name}}</a></li>
                <li class="breadcrumb-item active">Ver/Editar Miembro</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Información del Miembro</h5>
            <form action= "{{ route('orgs.members.update', [$org->id, $member->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="rut" class="form-label">RUT</label>
                            <input type="text" class="form-control @error('rut') is-invalid @enderror" id="rut" name="rut" value="{{ old('rut', $member->rut) }}" required readonly>
                            @error('rut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="first_name" class="form-label">Nombres</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $member->first_name) }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Apellidos</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $member->last_name) }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Dirección</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $member->address) }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                          <!-- NUEVO: Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $member->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                     <div class="mb-3 d-none">
                            <label for="city" class="form-label">Comuna</label>
                            <select class="form-select" id="city" name="city">
                                @if($city)
                                <option value="{{$city->id}}">{{$city->name_city}}</option>
                                @endif
                            </select>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    <!-- NUEVO: Género -->
                    
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="state" class="form-label">Región</label>
                            <select class="form-select" id="state" name="state">
                                <option value="">Seleccionar Región</option>
                                @foreach($states as $state)
                                <option value="{{$state->id}}" {{ $city->state_id == $state->id ? 'selected' : '' }}>{{$state->name_state}}</option>
                                @endforeach
                            </select>
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="province" class="form-label">Provincia</label>
                            <select class="form-select" id="province" name="province">
                                @if($city)
                                <option value="{{$city->province_id}}">{{$city->name_province}}</option>
                                @endif
                            </select>
                            @error('province')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                         <div class="mb-3">
                        <label for="mobilephone" class="form-label">Número de celular</label>
                        <input type="text" maxlength="15" class="form-control @error('mobilephone') is-invalid @enderror" id="mobilephone" name="mobilephone" value="{{ old('mobilephone', $member->mobile_phone) }}" required>
                        @error('mobilephone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NUEVO: Teléfono fijo -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Número de teléfono</label>
                        <input type="text" maxlength="15" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $member->phone) }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Género</label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                            <option value="">Seleccionar género</option>
                            <option value="MASCULINO" {{ old('gender', $member->gender) == 'MASCULINO' ? 'selected' : '' }}>Hombre</option>
                            <option value="FEMENINO" {{ old('gender', $member->gender) == 'FEMENINO' ? 'selected' : '' }}>Mujer</option>
                            <option value="OTRO" {{ old('gender', $member->gender) == 'OTRO' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    </div>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Servicios -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title d-flex justify-content-between align-items-center">
                Servicios Asociados
                <a href="{{ route('orgs.services.createForMember', [$org->id, $member->id]) }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-plus-circle"></i> Agregar Servicio
                </a>
            </h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Servicio</th>
                            <th>Sector</th>
                            <th>N°Medidor</th>
                            <th>Boleta/Factura</th>
                            <th>MIDEPLAN</th>
                            <th>Porcentaje</th>
                            <th>Diametro</th>
                            <th>Operaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($services->isNotEmpty())
                            @foreach($services as $service)
                                <tr>
                                    <td>{{ $service->nro ?? '' }}</td>
                                    <td>{{ $service->sector ?? 'N/D' }}</td>
                                    <td>{{ $service->meter_number ?? 'N/D' }}</td>
                                    <td>{{ $service->invoice_type ?? 'N/D' }}</td>
                                    <td>{{ $service->meter_plan ?? 'N/D' }}</td>
                                    <td>{{ $service->percentage ?? 'N/D' }}</td>
                                    <td>{{ $service->diameter ?? 'N/D' }}</td>
                                    <td>
                                        <a href="{{ route('orgs.members.services.edit', [$org->id, $member->id, $service->id]) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i> Editar</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2">No hay servicios disponibles para este miembro.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(function(){
          $("#state").change(function(e){
            var id ="";
              if ($("#state").val() !=null){
                id = $("#state").val();
              }
              $.ajax({
                  url: "/ajax/"+id+"/provincias",
                  type: "get",
                  dataType: "json",
                  data: {
                  'id': id,
                  },
                  success: function (resultado){
                    //alert(resultado);
                    $('#province').empty();
                    $('#city').empty();
                    $('#province').append('<option value="">Seleccionar Provincia</option>');
                    resultado.forEach(function(key,index){
                       $('#province').append('<option value="'+key.id+'">'+key.name_province+'</option>');
                    });
                  }
                });
          });
        });

        $(function(){
          $("#province").change(function(e){
            var id ="";
              if ($("#province").val() !=null){
                id = $("#province").val();
              }
              
          });
                        $.ajax({
                  url: "/ajax/"+id+"/comunas",
                  type: "get",
                  dataType: "json",
                  data: {
                  'id': id,
                  },
                  success: function (resultado){
                    //alert(resultado);
                    $('#city').empty();
                    $('#city').append('<option value="">Seleccionar Comuna</option>');
                    resultado.forEach(function(key,index){
                       $('#city').append('<option value="'+key.id+'">'+key.name_city+'</option>');
                    });
                  }
                });
        });
    </script>
@endsection
