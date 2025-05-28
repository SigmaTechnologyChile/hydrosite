@extends('layouts.nice', ['active'=>'orgs.locations.index','title'=>'Sectores'])

@section('content')
    <div class="pagetitle">
      <h1>{{$org->name}}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
          <li class="breadcrumb-item active">Sectores</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card top-selling overflow-auto">
            <div class="card-body pt-2">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <!-- Search Bar -->
                        <div class="search-container position-relative z-index-1">
                            <div class="search-backdrop rounded-2 p-1"></div>
                            <form method="GET" id="searchForm" class="position-relative">
                                <div class="input-group input-group-sm search-input-group">
                                    <span class="input-group-text border-0 bg-white ps-4">
                                        <i class="bi bi-search fs-4 text-primary"></i>
                                    </span>
                                    <input type="text" name="search" id="searchInput" class="form-control rounded-3 me-2"
                                        placeholder="Buscar por Sector" value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary px-4 rounded-end-3 pulse-btn">
                                        <span class="d-none d-md-inline">Buscar</span>
                                        <i class="bi bi-arrow-right d-inline d-md-none"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex justify-content-md-end align-items-center mt-3 mt-md-0">
                        <a href="{{route('orgs.locations.create',$org->id)}}" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm pulse-btn me-2">
                            <i class="bi bi-plus-circle-fill me-2"></i>Nuevo
                        </a>
                        <a href="{{ route('orgs.locations.export', $org->id) }}" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm pulse-btn"><!-- route -->
                            <i class="bi bi-box-arrow-right me-2"></i>Exportar
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Sector</th>
                                <th scope="col">Comuna</th>
                                <th scope="col">Región</th>
                                <th scope="col" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach($locations as $location)
                            <tr>
                                <td>{{ $location->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-light-primary rounded-circle me-2">
                                            <i class="ri-map-2-fill text-primary"></i>
                                        </div>
                                        <span class="fw-medium">{{$location->name}}</span>
                                    </div>
                                </td>
                                <td>{{$location->name_city}}</td>
                                <td>{{$location->name_state}}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Tooltip on top"><i class="bi bi-collection"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Ver"><i class="bi bi-check-circle"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Tooltip on top"><i class="bi bi-exclamation-octagon"></i></button>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Tooltip on top"><i class="bi bi-exclamation-triangle"></i></button>
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Tooltip on top"><i class="bi bi-info-circle"></i></button>
                                    <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Tooltip on top"><i class="bi bi-folder"></i></button>
                                    <a href="{{ route('orgs.locations.edit', [$org->id, $location->id]) }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Editar"><i class="bi bi-pencil"></i></a>

                                </td>
                            </tr>
                          @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {!! $locations->render('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </section>
@endsection
