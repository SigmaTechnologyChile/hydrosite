@extends('layouts.nice', ['active'=>'orgs.sections.create','title'=>'Crear Tramo'])

@section('content')
    <div class="pagetitle">
      <h1>{{$org->name}}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.sections.index',$org->id)}}">Tramos</a></li>
          <li class="breadcrumb-item active">Crear Tramo</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Crear nuevo Tramo</h5>

              <!-- Floating Labels Form -->
              <form class="row g-3"action="{{ route('orgs.sections.store',$org->id) }}" method="POST">
                @csrf
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="name" name="name" placeholder="100" min="1" required>
                    <label for="name">Nombre de Tramo</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="number" class="form-control" id="from_to" name="from_to" placeholder="100" min="1" required>
                    <label for="from_to">Desde</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="number" class="form-control" id="until_to" name="until_to" placeholder="100" min="1" required>
                    <label for="until_to">Hasta</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="number" class="form-control" id="cost" name="cost" placeholder="100" min="1" required>
                    <label for="cost">Valor $</label>
                  </div>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary"> Crear</button>
                  <button type="reset" class="btn btn-secondary">Borrar</button>
                </div>
              </form><!-- End floating Labels Form -->

            </div>
          </div>
    </section>
    
@endsection