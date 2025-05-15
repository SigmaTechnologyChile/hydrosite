@extends('layouts.nice', ['active' => 'orgs.categories.index', 'title' => 'Categorías'])

@section('content')
    <div class="pagetitle">
        <h1>{{ $org->name }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orgs.index') }}">Organizaciones</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orgs.dashboard', $org->id) }}">{{ $org->name }}</a></li>
                <li class="breadcrumb-item active">Categorías</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    
    <!-- Lista de categorías -->
    <div class="card">
        <div class="card-body">
            <h2 class="card-title d-flex justify-content-between align-items-center">
                Lista de Categorías
                <!-- Contenedor d-flex para alinear los botones a la derecha -->
                <div class="d-flex justify-content-end">
                    <a href="{{ route('orgs.categories.create', $org->id) }}" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm pulse-btn me-2">
                        <i class="fas fa-plus-circle me-2"></i> Crear Nueva Categoría
                    </a>
                    <a href="{{ route('orgs.categories.export', $org->id) }}" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm pulse-btn">
                        <i class="bi bi-box-arrow-right me-2"></i>Exportar
                    </a>
                </div>
            </h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                @if ($category->active)
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Activo</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <!-- Editar categoría -->
                                <a href="{{ route('orgs.categories.edit', [$org->id, $category->id]) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>

                                <!-- Formulario para eliminar con confirmación -->
                                <form action="{{ route('orgs.categories.destroy', [$org->id, $category->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginación -->
            {{ $categories->links() }}
        </div>
    </div><!-- End Card -->
@endsection
