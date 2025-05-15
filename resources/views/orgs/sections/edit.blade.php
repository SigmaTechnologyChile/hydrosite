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
    
    <!-- Edit Section Area -->
    <section id="section-edit" class="py-4">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-white py-3 border-bottom border-light">
                            <h5 class="card-title fs-4 mb-0 text-dark">Editar Tramo</h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('orgs.sections.update', [$org->id, $section->id]) }}">
                                @csrf
                                @method('PUT')
                        
                                <div class="row g-3 mb-4">
                                    <div class="col-md-5">
                                        <label for="name" class="form-label small text-muted">Nombre del Tramo</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-tag"></i></span>
                                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $section->name) }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <label for="from_to" class="form-label small text-muted">Desde</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-arrow-right-circle"></i></span>
                                            <input type="number" name="from_to" id="from_to" class="form-control" value="{{ old('from_to', $section->from_to) }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <label for="until_to" class="form-label small text-muted">Hasta</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-arrow-left-circle"></i></span>
                                            <input type="number" name="until_to" id="until_to" class="form-control" value="{{ old('until_to', $section->until_to) }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label for="cost" class="form-label small text-muted">Costo ($)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-currency-dollar"></i></span>
                                            <input type="number" name="cost" id="cost" class="form-control" value="{{ old('cost', $section->cost) }}" step="0.01" required>
                                        </div>
                                    </div>
                                </div>
                        
                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('orgs.sections.index', $org->id) }}" class="btn btn-secondary fw-semibold">
                                        <i class="bi bi-arrow-left me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary fw-semibold">
                                        <i class="bi bi-save me-2"></i>Guardar Cambios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection