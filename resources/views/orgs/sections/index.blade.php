@extends('layouts.nice', ['active' => 'orgs.sections.index', 'title' => 'Tramos'])

@section('content')
    <div class="pagetitle">
        <h1>{{$org->name}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.dashboard', $org->id)}}">{{$org->name}}</a></li>
                <li class="breadcrumb-item active">Tramos</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card top-selling overflow-auto">
            <div class="card-body pt-2">
                <form method="POST" action="{{ route('orgs.sections.storeFixedCost', $org->id) }}">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-2">
                            <label class="form-label">Valor cargo fijo:</label>
                            <input type="number" name="fixed_charge_penalty" class="form-control"
                                value="{{ old('fixed_charge_penalty', $fixedCostConfig->fixed_charge_penalty) }}"
                                placeholder="Ingresa el valor cargo fijo">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Interes cargo vencido:</label>
                            <input type="number" name="expired_penalty" class="form-control"
                                value="{{ old('expired_penalty', $fixedCostConfig->expired_penalty) }}"
                                placeholder="Ingresa el valor de interes cargo vencido">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Interes cargo Mora:</label>
                            <input type="number" name="late_fee_penalty" class="form-control"
                                value="{{ old('late_fee_penalty', $fixedCostConfig->late_fee_penalty) }}"
                                placeholder="Ingresa el valor de interes cargo Mora">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Valor corte y reposición</label>
                            <input type="number" name="replacement_penalty" class="form-control"
                                value="{{ old('replacement_penalty', $fixedCostConfig->replacement_penalty) }}"
                                placeholder="Ingresa el valor corte y reposición">
                        </div>
                        <div class="col-md-1">
                            <label for="max_covered_m3" class="form-label">Máximo m³ cubierto</label>
                            <input type="number" step="0" name="max_covered_m3" id="max_covered_m3" class="form-control"
                                value="{{ old('max_covered_m3', $fixedCostConfig->max_covered_m3) }}"
                                placeholder="Ingresa el max. m3 del subsidio">
                        </div>
                        <!-- Botón Filtrar -->
                        <div class="col-md-3 d-flex justify-content-md-start align-items-center mt-3 mt-md-0">
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm pulse-btn m-2"><i class="bi bi-save me-2"></i>Guardar</button>
                               <a href="{{ route('orgs.sections.export', $org->id) }}"
                                class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm pulse-btn"><!-- route -->
                                <i class="bi bi-box-arrow-right me-2"></i>Exportar
                            </a>
                        </div>

                         <div class="col-md-2 mt-3">
                             <a href="{{route('orgs.sections.create', $org->id)}}"
                                class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm pulse-btn me-2">
                                <i class="bi bi-plus-circle-fill me-2"></i>Nuevo tramo
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
                            @foreach($tiers as $tier)
                                <tr data-id="{{ $tier->id }}">
                                    <td>{{ $tier->id }}</td>
                                    <td>{{ $tier->tier_name}}</td>
                                    <td><input class="form-text" type="number" name="range_from" value="{{$tier->range_from}}"
                                            readonly></td>
                                    <td><input class="form-text" type="number" name="range_to" value="{{$tier->range_to}}"
                                            readonly></td>
                                    <td><input class="form-text" type="number" name="value" value="{{$tier->value}}" readonly>
                                    </td>
                                    <td class="text-center">
                                       <a href="{{ route('orgs.sections.edit', ['id' => $org->id, 'tramoId' => $tier->id]) }}"
                                            class="btn btn-sm btn-success">
                                            <i class="bi bi-pencil-square me-1"></i> Editar
                                        </a>
                                        <a href="{{ route('orgs.sections.destroy', ['id' => $org->id, 'tramoId' => $tier->id]) }}"
                                        class="btn btn-sm btn-danger delete-tier"
                                        data-tier-name="{{ $tier->tier_name }}">
                                            <i class="bi bi-trash me-1"></i> Eliminar
                                        </a>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{ $tiers->links('pagination::bootstrap-4') }}
    </section>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar eliminación de tramos con SweetAlert
    const deleteButtons = document.querySelectorAll('.delete-tier');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const url = this.getAttribute('href');
            const tierName = this.getAttribute('data-tier-name');

            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Deseas eliminar el tramo "${tierName}"? Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Crear formulario dinámicamente para enviar DELETE
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;

                    // Token CSRF
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    // Method DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    // Agregar al DOM y enviar
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            icon: 'success',
            timer: 3000,
            showConfirmButton: false
        });
    });
</script>
@endif
