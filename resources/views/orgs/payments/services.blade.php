@extends('layouts.nice', ['active'=>'orgs.payments.index','title'=>'Seleccionar Servicios'])

@section('content')
    <div class="pagetitle">
      <h1>Seleccionar Servicios</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('orgs.index') }}">Organizaciones</a></li>
          <li class="breadcrumb-item"><a href="{{ route('orgs.dashboard', $org->id) }}">{{ $org->name }}</a></li>
          <li class="breadcrumb-item active">Seleccionar Servicios</li>
        </ol>
      </nav>
    </div>

    <div class="col-12">
      <div class="card">
        <div class="card-body m-2">
           <div class="mb-4">
              <h4><strong>{{ $member->first_name }} {{ $member->last_name }}, tienes los siguientes servicios:</strong></h4>
            </div>
            <form method="POST" action="{{ route('orgs.orders.store', $org->id) }}">
            @csrf
             
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th scope="col">N° Servicio</th>
                    <th scope="col">Nombre del Sector</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Total ($)</th>
                    <th scope="col">Seleccionar</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($services as $service)
                  <tr>
                    <td><span class="badge bg-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="ID unico interno # {{ $service->id }}">{{ Illuminate\Support\Str::padLeft($service->nro,5,0) }}</span></td>
                    <td>{{ ucwords(str_replace('_', ' ', strtolower($service->sector))) }}</td>
                    
                    <td>
                        @if($service->total_amount)
                            <span class="badge bg-warning ">Pendiente de pago</span>
                        @else
                            <span class="badge bg-success">Sin deudas</span>
                        @endif
                    </td>
                    <td class="text-end">@money($service->total_amount)</td>
                    <td>
                        @if($service->total_amount)
                        <input type="checkbox" class="service-checkbox" data-total="{{ $service->total_amount }}" value="{{ $service->id }}" name="services[]">
                        @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            
            <div class="mt-3">
              <strong>Total Seleccionado: </strong>
              <span id="totalAmount">$0</span>
            </div>

            <!-- Bloque de forma de pago deshabilitado inicialmente -->
            <div class="mt-4 text-center" id="paymentMethods" style="display:none;">
              <label class="form-label d-block mb-3"><strong>Seleccione la forma de pago:</strong></label>
              
              <div class="d-inline-flex justify-content-center gap-3">
                <div>
                  <input type="radio" class="btn-check" name="payment_method_id" id="pos" value="1" autocomplete="off" required>
                  <label class="btn btn-outline-primary" for="pos">POS</label>
                </div>
                <div>
                  <input type="radio" class="btn-check" name="payment_method_id" id="efectivo" value="2" autocomplete="off" required>
                  <label class="btn btn-outline-primary" for="efectivo">Efectivo</label>
                </div>
                <div>
                  <input type="radio" class="btn-check" name="payment_method_id" id="transferencia" value="3" autocomplete="off" required>
                  <label class="btn btn-outline-primary" for="transferencia">Transferencia</label>
                </div>
              </div>
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary" id="payButton" disabled>Continuar</button>
            </div>
            
          </form>
    
        </div>
      </div>
    </div>

    <script>
      // Función para actualizar el total cuando se seleccionan o deseleccionan los servicios
      document.querySelectorAll('.service-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
          let totalAmount = 0;
          document.querySelectorAll('.service-checkbox:checked').forEach(function(checkedBox) {
            totalAmount += parseFloat(checkedBox.getAttribute('data-total'));
          });
          
          // Mostrar el total
          document.getElementById('totalAmount').textContent = totalAmount.toLocaleString('es-CL', { style: 'currency', currency: 'CLP' });

          // Habilitar o deshabilitar la forma de pago
          if (totalAmount > 0) {
            document.getElementById('paymentMethods').style.display = 'block';
          } else {
            document.getElementById('paymentMethods').style.display = 'none';
          }

          // Activar o desactivar el botón de pagar
          document.getElementById('payButton').disabled = totalAmount === 0;
        });
      });
    </script>
@endsection

