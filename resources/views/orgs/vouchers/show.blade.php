@extends('layouts.nice', ['active' => 'orgs.vouchers.show', 'title' => 'Voucher de Orden'])

@section('content')
    <div class="container py-5" style="max-width: 600px;">
    
    
        <div class="border p-4 rounded shadow-sm bg-light">
            <div class="text-center mb-4">
                <h4 class="fw-bold text-primary">Comité de Agua Potable Rural Las Arboledas Teno</h4>
                <p class="mb-1 text-muted">RUT: 71.108.700-1</p>
                <p class="mb-1 text-muted">Tel: +569 52534790</p>
                <p class="mb-1 text-muted">comiteaprlasarboledas@gmail.com</p>
            </div>
            @foreach($orderItems as $item)
            <hr class="my-4">
    
            <div class="mb-3">
                <h5 class="fw-bold">Detalle del Pago Folio: {{$item->folio}}</h5>
                <div class="row">
                    <div class="col-6">
                        <p><strong>Pago realizado con:</strong>
                            @if($order->payment_method_id == 1)
                                <span class="badge bg-success">POS</span>
                            @elseif($order->payment_method_id == 2)
                                <span class="badge bg-success">Efectivo</span>
                            @elseif($order->payment_method_id == 3)
                                <span class="badge bg-info">Transferencia</span>
                            @else
                                <span class="badge bg-secondary">Otro</span>
                            @endif
                        </p>    
                    
                    </div>
                    <div class="col-6">
                        <p><strong>Descuento:</strong>  
                            @if($order->discount > 0)
                                <span class="text-success">${{ number_format($order->discount, 0, ',', '.') }}</span>
                            @else
                                <span class="text-muted">No aplica</span>
                            @endif
                        </p>                        
                    </div>
                    <div class="col-6">
                        <p><strong>Costo Servicio:</strong> <span class="fw-bold text-danger">@money($order->commission)</span></p>
                    </div>
                    <div class="col-6">
                        <p><strong>Total: </strong> <span class="fw-bold text-danger">@money($item->total)</span></p>
                    </div>
                </div>
            </div>
            @endforeach
            <hr class="my-4">
    
            <div class="mb-3">
                <h6 class="fw-bold">Información del Servicio</h6>
                <div class="row">
                    <p class="col-6"><strong>N° de orden:</strong> {{ $order->order_code }}</p>
                    <p class="col-6"><strong>Fecha:</strong> {{ $order->created_at->format('d-m-Y') }}</p>
                    <p class="col-5"><strong>RUT/RUN:</strong> {{ $order->dni }}</p>
                    <p class="col-7"><strong>Cliente:</strong> {{ $order->name }}</p>
                </div>
            </div>
            
            <hr class="my-4">
    
            <div class="text-center mb-4">
                <p class="fw-bold text-success">¡Muchas gracias por preferirnos!</p>
                <p class="mb-1">Ante cualquier reclamo o sugerencia, por favor comuníquese a nuestro correo:</p>
                <p><strong>comiteaprlasarboledas@gmail.com</strong></p>
                <p><a href="https://www.hydrosite.cl" class="text-decoration-none text-primary">www.hydrosite.cl</a></p>
            </div>
    
            <div class="text-center">
                <button onclick="window.print()" class="btn btn-success btn-lg"><i class="bi bi-printer"></i> Imprimir</button>
            </div>
        </div>
    
    
    </div>
@endsection
<style>
@media print {
    @page {
        size: A4;
        margin: 0;
    }

    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    body * {
        visibility: hidden;
    }

    .container, .container * {
        visibility: visible;
    }

    .container {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        padding: 0;
        position: relative;
        top: 0;
        left: 0;
        transform: none;
        box-shadow: none !important;
        background: white !important;
        page-break-after: avoid;
    }

    /* Oculta partes no deseadas */
    header, footer, nav, .navbar, .sidebar,
    .btn, button {
        display: none !important;
    }
}
</style>