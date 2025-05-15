<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Member;
use App\Models\Service;
use App\Models\Reading;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    private $comissionRate = 1600;
    private $currencyValue = 990;
    
    
    public function Store(Request $request)
    {
        //dd($request);
        
        $member = Member::where('rut', $request->input('dni'))->first();
        
        $Order = new Order;
        $Order->order_code      = Str::upper(Str::random(9));
        $Order->dni             = $member->rut;
        $Order->name            = $member->full_name;
        $Order->email           = $member->email;
        $Order->phone           = $member->phone;
        $Order->status          = 1;
        $Order->payment_method_id = 4;//Webpay Plus
        $Order->save();
        $order_id = $Order->id;
        //dd($order_id);
        
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
            $order_items->payment_method_id = 4;//Webpay Plus
            $order_items->description="Pago de servicio nro <b>".Str::padLeft($service->nro,5,0)."</b> , Periodo <b>".$reading->period."</b>, lectura <b>".$reading->id."</b>";
            $order_items->save(); 
            
            $sumTotal=+$reading->total;
        }

        $orderU = Order::findOrFail($order_id);
        $orderU->qty = $qty;
        $orderU->total = $sumTotal;
        $orderU->save();
        
        return redirect()->route('checkout-order-code',[$Order->order_code]);
    }
    
    public function show($order_code)
    {
        $order = Order::where('order_code',$order_code)->first();
        //$order = Order::findOrFail($id);
        $items = OrderItem::where('order_id',$order->id)->get();
                            
//dd($items);
        return view('checkout', ['order' => $order,'items' => $items]);
    }
    
    public function update(Request $request, $order_code)
    {
       //dd($request->input()); 
       
        $ticket_ids = $request->input('ticket_id');
        //$ticket_dnis         = $request->input('ticket_dni');
        $ticket_first_names  = $request->input('ticket_firstName');
        $ticket_last_names   = $request->input('ticket_lastName');
        $ticket_emails       = $request->input('ticket_email');
        //$ticket_phones      = $request->input('ticket_phone');
            
        for($i = 0; $i < count($ticket_ids); $i++){
            $ticket_id = @$ticket_ids[$i];
            $order_ticket = OrderTicket::findOrFail($ticket_id);
            //$order_ticket->dni         = $ticket_dnis[$i];
            $order_ticket->first_name  = $ticket_first_names[$i];
            $order_ticket->last_name   = $ticket_last_names[$i];
            $order_ticket->email       = $ticket_emails[$i];
            //$order_ticket->phone       = $ticket_phones[$i];
            $order_ticket->save();
        }
        
        //$orderU = Order::findOrFail($order_id);
        $orderU = Order::where('order_code',$order_code)->first();
        $orderU->validate_tickets=1;
        $orderU->status=2;
        $orderU->save();
        
        return redirect()->route('checkout-order-code',[$order_code]);
    }
    
    public function recovery_update($order_code)
    {
        $orderU = Order::where('order_code',$order_code)->first();
        $orderU->validate_tickets=0;
        $orderU->save();
        
        return redirect()->route('checkout-order-code',[$order_code]);
    }
}
