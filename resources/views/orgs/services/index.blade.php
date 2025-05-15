@extends('layouts.nice', ['active' => 'orgs.services.index', 'title' => 'Lista de Servicios'])

@section('content')
    <div class="pagetitle">
        <h1>Lista de Servicios para la Organización: {{ $org->name }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orgs.index') }}">Organizaciones</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orgs.dashboard', $org->id) }}">{{ $org->name }}</a></li>
                <li class="breadcrumb-item active">Lista de Servicios</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card top-selling overflow-auto">
            <div class="card-body pt-2">
                <!-- Filtros -->
                <form action="" method="GET" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <!-- Sector -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Sector</label>
                            <select name="sector" id="sector" class="form-select rounded-3">
                                @if($locations->isNotEmpty())
                                    <option value="">Todos</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('sector', request()->sector) == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="">No hay sectores disponibles</option>
                                @endif
                            </select>
                        </div>

                        <!-- N° Servicio -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">N° ID Servicio</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                <input type="text" class="form-control" name="nro" id="nro" value="{{ request()->nro }}">
                            </div>
                        </div>

                        <!-- Botón Filtrar -->
                        <div class="col-md-3 d-flex">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill pulse-btn">
                                <i class="bi bi-funnel-fill me-2"></i>Filtrar
                            </button>
                        </div>

                        <!-- Botón Exportar -->
                        <div class="col-md-3 d-flex">
                            <a href="{{ route('orgs.services.export', $org->id) }}" class="btn btn-primary w-100 rounded-pill pulse-btn">
                                <i class="bi bi-download me-2"></i>Exportar
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabla de Servicios -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">ID Servicio</th>
                                <th scope="col">Miembro</th>
                                <th scope="col">Sector</th>
                                <th scope="col">N° Medidor</th>
                                <th scope="col">Boleta/Factura</th>
                                <th scope="col">MIDEPLAN</th>
                                <th scope="col">Porcentaje</th>
                                <th scope="col">Diámetro</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if($services->isNotEmpty())
                                @foreach($services as $service)
                                    <tr>
                                        <td>{{ $service->nro ?? 'N/D' }}</td>
                                        <td>{{ $service->member_name ?? 'N/D' }}</td>
                                        <td>{{ $service->location_name }}</td>
                                        <td>{{ $service->meter_number ?? 'N/D' }}</td>
                                        <td>{{ $service->invoice_type ?? 'N/D' }}</td>
                                        <td>{{ $service->meter_plan ?? 'N/D' }}</td>
                                        <td>{{ $service->percentage ?? 'N/D' }}</td>
                                        <td>{{ $service->diameter ?? 'N/D' }}</td>
                                       
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9">No hay servicios disponibles para esta organización.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-4">
                    {!! $services->render('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .icon-box {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .bg-light-primary {
            background-color: rgba(var(--bs-primary-rgb), 0.1);
        }
        
        .table > :not(caption) > * > * {
            padding: 0.75rem 1rem;
        }
        
        .search-container {
            margin-bottom: 1.5rem;
        }
        
        @media (max-width: 767.98px) {
            .table-responsive {
                border: 0;
            }
        }

        .modal-header {
            border-radius: 0.375rem 0.375rem 0 0;
        }

        .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        /* Estilos para el modal */
        .modal-header {
            border-bottom: 0;
        }

        .modal-content {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
        }

        .form-control:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
        }

        .btn-close-white {
            filter: brightness(0) invert(1);
        }

        /* Estilos para el formulario de dos columnas */
        @media (max-width: 767.98px) {
            .modal-body .border-end {
                border-right: none !important;
                border-bottom: 1px solid #dee2e6;
                padding-bottom: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .modal-body .ps-md-4 {
                padding-left: 0 !important;
            }

            .modal-body .pe-md-4 {
                padding-right: 0 !important;
            }
        }

        .modal-xl {
            max-width: 1140px;
        }

        textarea.form-control {
            min-height: 100px;
        }

        .text-primary {
            color: var(--bs-primary) !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filterForm');
            if (form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    alert('Filtro aplicado correctamente');
                });
            }
        });
    </script>
@endpush
