@extends('layouts.nice', ['active'=>'orgs.notifications.index', 'title'=>'Notificaciones'])

@section('content')
    <div class="pagetitle">
        <h1>{{$org->name}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
                <li class="breadcrumb-item active">Notificaciones</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <!-- Estadísticas rápidas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 text-white">Total</h6>
                                <h3 class="mb-0">124</h3>
                            </div>
                            <i class="bi bi-bell fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 text-white">Enviadas</h6>
                                <h3 class="mb-0">98</h3>
                            </div>
                            <i class="bi bi-check-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 text-white">Pendientes</h6>
                                <h3 class="mb-0">18</h3>
                            </div>
                            <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 text-white">Fallidas</h6>
                                <h3 class="mb-0">8</h3>
                            </div>
                            <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!-- Formulario de selección y envío -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Enviar Notificación</h5>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#notificationForm">
                <i class="bi bi-chevron-up"></i>
            </button>
        </div>
        
        <div class="collapse show" id="notificationForm">
            <form action="{{ route('orgs.notifications.store', ['id' => $org->id]) }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="notification_title" class="form-label">Título de la notificación:</label>
                        <input type="text" id="notification_title" name="title" class="form-control" required placeholder="Ingrese un título descriptivo">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="notification_message" class="form-label">Mensaje:</label>
                        <textarea id="notification_message" name="message" class="form-control" rows="4" required placeholder="Escriba el contenido detallado de la notificación..."></textarea>
                        <div class="form-text">Describe el contenido de la notificación detalladamente.</div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label d-block">Métodos de envío:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="send_app" name="send_methods[]" value="app" checked disabled>
                            <label class="form-check-label" for="send_app">Aplicación (predeterminado)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="send_email" name="send_methods[]" value="email">
                            <label class="form-check-label" for="send_email">Correo electrónico</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="send_sms" name="send_methods[]" value="sms">
                            <label class="form-check-label" for="send_sms">SMS</label>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <!-- Selector de destinatarios -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="target_type" class="form-label">Enviar a:</label>
                        <select id="target_type" name="target_type" class="form-select" required>
                            <option value="sector">Sector</option>
                            <option value="person">Persona</option>
                        </select>
                    </div>
                </div>
                
                <!-- Contenedor para los selectores dinámicos -->
                <div id="dynamic-selectors">
                    <div class="row mb-3 selector-container" id="sector-container">
                        <div class="col-md-12">
                            <label for="sectors" class="form-label">Seleccionar Sectores:</label>
                            <select id="sectors" name="sectors[]" class="form-select" multiple required>
                                @foreach ($activeLocations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Mantén presionado Ctrl (o Cmd en Mac) para seleccionar múltiples sectores.</div>
                        </div>
                    </div>
                </div>
                
                <div class="text-end">
                    <button type="reset" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i> Enviar Notificación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


        <!-- Listado de notificaciones -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Listado de Notificaciones</h5>
                    <div>
                        <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters">
                            <i class="bi bi-filter"></i> Filtrar
                        </button>
                        <a href="#" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Nueva Notificación
                        </a>
                    </div>
                </div>

                <!-- Buscador -->
                <div class="row mb-3">
                    <div class="col-md-6 offset-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Buscar notificaciones...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filtros colapsables -->
                <div class="collapse mb-3" id="collapseFilters">
                    <div class="card card-body bg-light">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Fecha desde</label>
                                <input type="date" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha hasta</label>
                                <input type="date" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Estado</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    <option value="sent">Enviados</option>
                                    <option value="pending">Pendientes</option>
                                    <option value="failed">Fallidos</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Método de envío</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    <option value="app">Aplicación</option>
                                    <option value="email">Email</option>
                                    <option value="sms">SMS</option>
                                </select>
                            </div>
                            <div class="col-12 text-end">
                                <button class="btn btn-sm btn-outline-secondary me-2">Limpiar</button>
                                <button class="btn btn-sm btn-secondary">Aplicar filtros</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Título</th>
                                <th>Mensaje</th>
                                <th>Destinatarios</th>
                                <th>Métodos</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Actualización del sistema</td>
                                <td>El sistema se actualizará esta noche a las 22:00.</td>
                                <td>
                                    <span class="badge bg-primary">Sector: Administración</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border"><i class="bi bi-app me-1"></i>App</span>
                                    <span class="badge rounded-pill bg-light text-dark border"><i class="bi bi-envelope me-1"></i>Email</span>
                                </td>
                                <td><span class="badge bg-success">Enviado</span></td>
                                <td>08/04/2025 12:34</td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye"></i> Ver detalles</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil"></i> Editar</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-arrow-repeat"></i> Reenviar</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash"></i> Eliminar</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Mantenimiento programado</td>
                                <td>Habrá una pausa por mantenimiento el viernes.</td>
                                <td>
                                    <span class="badge bg-info">Todos los sectores</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border"><i class="bi bi-app me-1"></i>App</span>
                                </td>
                                <td><span class="badge bg-success">Enviado</span></td>
                                <td>06/04/2025 09:15</td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye"></i> Ver detalles</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil"></i> Editar</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-arrow-repeat"></i> Reenviar</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash"></i> Eliminar</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Nuevo módulo disponible</td>
                                <td>Ya puedes acceder al nuevo módulo de reportes.</td>
                                <td>
                                    <span class="badge bg-secondary">Persona: Juan Pérez</span>
                                    <span class="badge bg-secondary">+2 más</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border"><i class="bi bi-app me-1"></i>App</span>
                                    <span class="badge rounded-pill bg-light text-dark border"><i class="bi bi-envelope me-1"></i>Email</span>
                                    <span class="badge rounded-pill bg-light text-dark border"><i class="bi bi-chat-dots me-1"></i>SMS</span>
                                </td>
                                <td><span class="badge bg-warning text-dark">Parcial</span></td>
                                <td>01/04/2025 16:50</td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye"></i> Ver detalles</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil"></i> Editar</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-arrow-repeat"></i> Reenviar</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash"></i> Eliminar</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Recordatorio de reunión</td>
                                <td>Recordatorio: Reunión de equipo mañana a las 10:00.</td>
                                <td>
                                    <span class="badge bg-primary">Sector: Desarrollo</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border"><i class="bi bi-app me-1"></i>App</span>
                                    <span class="badge rounded-pill bg-light text-dark border"><i class="bi bi-envelope me-1"></i>Email</span>
                                </td>
                                <td><span class="badge bg-danger">Fallido</span></td>
                                <td>31/03/2025 14:22</td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye"></i> Ver detalles</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil"></i> Editar</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-arrow-repeat"></i> Reenviar</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash"></i> Eliminar</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <nav aria-label="Page navigation" class="mt-3">
                    <ul class="pagination justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        .badge {
            font-weight: 500;
        }
        .table > :not(caption) > * > * {
            padding: 0.75rem 0.75rem;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .card-title {
            font-weight: 600;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const targetTypeSelect = document.getElementById('target_type');
            const sectorContainer = document.getElementById('sector-container');
            const personContainer = document.getElementById('person-container');
            
            function toggleSelectors() {
                document.querySelectorAll('.selector-container').forEach(container => {
                    container.style.display = 'none';
                });
                
                if (targetTypeSelect.value === 'sector') {
                    sectorContainer.style.display = 'block';
                } else if (targetTypeSelect.value === 'person') {
                    personContainer.style.display = 'block';
                }
            }
            
            targetTypeSelect.addEventListener('change', toggleSelectors);
            toggleSelectors();
            setTimeout(toggleSelectors, 100);
            
            if (typeof $.fn.summernote !== 'undefined') {
                $('#notification_message').summernote({
                    placeholder: 'Escribe el contenido de la notificación...',
                    tabsize: 2,
                    height: 120,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']]
                    ]
                });
            }

            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @endpush
@endsection