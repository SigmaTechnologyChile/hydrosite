@extends('layouts.nice', ['active'=>'packages-create','title'=>'Crear Suscripción'])

@section('content')
    <div class="pagetitle">
      <h1>Crear Paquete</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('packages.index')}}">Paquetes</a></li>
          <li class="breadcrumb-item active">Crear Paquete</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Lista de Paquete</h5>
              <p>Gestión de Organizaciones Paquete</p>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-octagon me-1"></i>
                        Modulo no habilitado!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
            </div>
          </div>
    </section>
@endsection