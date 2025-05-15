@extends('layouts.nice', ['active'=>'orgs.sections.index','title'=>'Tramos'])

@section('content')
    <div class="pagetitle">
      <h1>{{$org->name}}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
          <li class="breadcrumb-item active">Tramos</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section dashboard">
        <div class="card top-selling overflow-auto">
            <div class="card-body pt-2">
                <form method="POST" action="{{route('orgs.fixed_charge_store',$org->id)}}">
                    @csrf
                    <div class="row align-items-end">
                        <!-- Buscador -->
                        <div class="col-md-2">
                            <label class="form-label">Valor cargo fijo:</label>
                            <input type="number" name="fixed_charge" class="form-control" value="{{$org->fixed_charge}}" placeholder="Ingresa el valor cargo fijo">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Interes cargo vencido:</label>
                            <input type="number" name="interest_due" class="form-control" value="{{$org->interest_due}}" placeholder="Ingresa el valor de interes cargo vencido">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Interes cargo Mora:</label>
                            <input type="number" name="interest_late" class="form-control" value="{{$org->interest_late}}" placeholder="Ingresa el valor de interes cargo Mora">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Valor corte y reposición</label>
                            <input type="number" name="replacement_cut" class="form-control" value="{{$org->replacement_cut}}" placeholder="Ingresa el valor corte y reposición">
                        </div>
                        <!-- Botón Filtrar -->
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">Guardar</button>
                        </div>
                        <div class="col-md-3 d-flex justify-content-md-end align-items-center mt-3 mt-md-0">
                            <a href="{{route('orgs.sections.create',$org->id)}}" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm pulse-btn me-2">
                                <i class="bi bi-plus-circle-fill me-2"></i>Nuevo
                            </a>
                            <a href="{{ route('orgs.sections.export',$org->id) }}" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm pulse-btn"><!-- route -->
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
                                <th scope="col">#</th>
                                <th scope="col">Tramo</th>
                                <th scope="col">Desde</th>
                                <th scope="col">Hasta</th>
                                <th scope="col">Valor $</th>
                                <th scope="col" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sections as $section)
                            <tr data-id="{{ $section->id }}">
                                <td>{{ $section->id }}</td>
                                <td>{{ $section->name }}</td>
                                <td><input class="form-text" type="number" name="from_to" value="{{$section->from_to}}" readonly></td>
                                <td><input class="form-text" type="number" name="until_to" value="{{$section->until_to}}" readonly></td>
                                <td><input class="form-text" type="number" name="cost" value="{{$section->cost}}" readonly></td>
                                <td class="text-center">
                                    <a href="{{ route('orgs.sections.edit', [$org->id, $section->id]) }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-pencil-square me-1"></i> Editar
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {!! $sections->render('pagination::bootstrap-4') !!}
    </section>
@endsection