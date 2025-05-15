<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Service;
use App\Models\Reading;
use App\Models\Member;
use App\Models\Org;
use Illuminate\Support\Str;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\PaymentMethod;

class OrdersController extends Controller
{
    protected $_param;
    public $org;
    
    public function __construct()
    {
        $this->middleware('auth');
        $id = \Route::current()->Parameter('id');
        $this->org = Org::find($id);
    }
    
    
    public function store(Request $request, $org_id)
    {
        //dd($request);
        
        $validated = $request->validate([
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
            'payment_method_id' => 'required|string|in:1,2,3',
            
        ]);
    
        // Validar que haya servicios seleccionados
        if (!$request->has('services') || empty($request->services)) {
            return redirect()->back()->with('error', 'Debes seleccionar al menos un servicio');
        }
    
        $member = Member::where('rut', $request->input('dni'))->first(); 
        
        $firstService = Service::find($validated['services'][0]);
        
        if (!$firstService) {
            return redirect()->back()->with('error', 'No se encontró el servicio seleccionado');
        }
        
        $member = Member::find($firstService->member_id);
        
        if (!$member) {
            return redirect()->back()->with('error', 'No se encontró el socio asociado al servicio');
        }
        
        $payment_method_id = $request->input('payment_method_id');
        if($payment_method_id == 1 OR $payment_method_id == 2 OR $payment_method_id == 3){
            $payment_status = 1;
        }else{
            $payment_status= 0;
        }

        // Crear la nueva orden
        $order = new Order();
        $order->order_code = Str::upper(Str::random(9));
        //$order->org_id = $org_id;
        $order->dni = $request->input('dni');
        $order->name = $member->first_name . ' ' . $member->last_name;
        $order->email = $member->email;
        $order->phone = $member->phone;
        $order->status = 1; // Estado de la orden (pendiente)
        $order->payment_method_id = $payment_method_id;
        $order->payment_status = $payment_status;
        $order->save();

        $order_id = $order->id;
        $docs = $request->input('doc');
        $services = $request->input('services');

        //dd($services);
        $qty=0;
        $sum=0;
        foreach($services as $key=>$service){
            $service_id = $service;
            $service = Service::find($service_id);
            $reading = Reading::where('service_id',$service_id)->where('payment_status',0)->orderby('period','DESC')->first();
            
            $qty++;
            $order_items = new OrderItem;
            $order_items->order_id = $order_id;
            $order_items->org_id = $reading->org_id;
            $order_items->member_id = $reading->member_id;
            $order_items->service_id = $reading->service_id;
            $order_items->reading_id  = $reading->id;
            $order_items->locality_id = $service->locality_id;
            $order_items->folio=$reading->folio;
            $order_items->type_dte = ($service->invoice_type=='factura' ? 'Factura' :'Boleta');
            $order_items->price=$reading->total_mounth;
            $order_items->total=$reading->total;
            $order_items->status=1;
            $order_items->payment_method_id = $payment_method_id;
            $order_items->description="Pago de servicio nro <b>".Str::padLeft($service->nro,5,0)."</b> , Periodo <b>".$reading->period."</b>, lectura <b>".$reading->id."</b>";
            $order_items->payment_status = $payment_status;
            $order_items->save(); 
            $sumTotal=+$reading->total;
            
            $reading->payment_status = $payment_status;
            $reading->save();            
        }

        $orderU = Order::findOrFail($order_id);
        $orderU->qty = $qty;
        $orderU->total = $sumTotal;
        $orderU->save();
        
        //dd(route('orders.show', $order->order_code)); // Esto debería mostrar la URL completa
        return redirect()->route('orgs.orders.show', ['id' => $org_id, 'order_code' => $order->order_code]);
    
    }

    public function show($org_id, $order_code)
    {
        //dd($order_code);
        //dd($request->all());
        $org = Org::findOrFail($org_id); 
        //$order = Order::where('order_code', $order_code)->firstOrFail();
        // Carga la orden con la relación 'items'
        $order = Order::with('items')->where('order_code', $order_code)->firstOrFail();
    
        // Obtiene los ítems de la orden
        $items = $order->items;

        return view('orgs.orders.show', compact('org', 'order', 'items'));
    
    }

    private function getPaymentMethodId($paymentMethod)
    {
        if($paymentMethod = PaymentMethod::where('title',$paymentMethod)->first()){
            return $paymentMethod->id;
        }else{
           return 0; 
        }
    }

    private function createOrderItems($order, $services)
    {
        $total = 0;
        foreach ($services as $service) {
            dd($service->total_amount, $service->price);
            // Verifica si el servicio tiene un precio válido
            $price = $service->total_amount ?? $service->price ?? 0; 
            
            // Si el precio es 0, puedes optar por lanzar un error o hacer algo específico
            if ($price == 0) {
                return redirect()->back()->with('error', 'El servicio ' . $service->sector . ' no tiene un precio válido.');
            }
    
            $item = new OrderItem();
            $item->order_id = $order->id;
            $item->org_id = $order->org_id;
            $item->service_id = $service->id;
            $item->doc_id = $service->doc_id ?? 1;
            $item->description = "Pago de servicio: " . $service->sector;
            $item->price = $price;
            $item->qty = 1;
            $item->total = $price;
            $item->status = 1;
            $item->save();
    
            $total += $price;
        }
    
    
        $order->qty = $services->count();
        $order->total = $total;
        $order->save();
    }
}