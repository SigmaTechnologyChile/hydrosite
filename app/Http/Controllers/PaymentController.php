<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Mail\OrderMail;

class PaymentController extends Controller
{
	/*WEBPAY DIRECTO*/
	
    function get_ws($data,$method,$type,$endpoint){
        $curl = curl_init();
        if($type=='live'){
    		$TbkApiKeyId='597048495722';
    		$TbkApiKeySecret='0f71d1c8-2a99-4dcc-af45-5e5ab608e120';
            $url="https://webpay3g.transbank.cl".$endpoint;//Live
        }else{
    		$TbkApiKeyId='597055555532';
    		$TbkApiKeySecret='579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C';
            $url="https://webpay3gint.transbank.cl".$endpoint;//Testing
        }
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $method,
          CURLOPT_POST => true,
          CURLOPT_POSTFIELDS => $data,
          CURLOPT_HTTPHEADER => array(
            'Tbk-Api-Key-Id: '.$TbkApiKeyId.'',
            'Tbk-Api-Key-Secret: '.$TbkApiKeySecret.'',
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        //echo $response;
        return json_decode($response);
    }	
	
	function create_order_payment($order_code)
	{
	    try {
    	    $order = Order::where('order_code',$order_code)->first();
    
            if($order->total > 0 AND $order->payment_status==0){
                
                $user_id = $order->id;
                $sub_total = $order->sub_total;
                $commission = $order->commission;
                $order_total = $order->total;
                
                $OrderItem = OrderItem::where('order_id',$order->id)->get();
    
                $item = json_encode($OrderItem);
                
                $return_url = \URL::to('/payment-response/'.$order->order_code);
                
                $buy_order=rand();
                $session_id=rand();

                    $data='{
                            "buy_order": "'.$order->order_code.'",
                            "session_id": "'.$session_id.'",
                            "amount": '.$order_total.',
                            "return_url": "'.$return_url.'"
                            }';
                    $method='POST';
                    $endpoint='/rswebpaytransaction/api/webpay/v1.0/transactions';
                    
                    $response = $this->get_ws($data,$method,"sandbox",$endpoint);
                    //dd($response);
                    //$url_tbk = $response->url;
                    //$token = $response->token;

                if(isset($response->token) AND isset($response->url))
                {
                    $order_payment = new OrderPayment;
                    $order_payment->order_id = $order->id;
                    $order_payment->response = json_encode($response);
                    $order_payment->method='WebPays plus';
                    $order_payment->status=0;
                    $order_payment->save();    

                    $token= $response->token;
                    $url = $response->url;
                    return view('webpay', compact('url', 'token'));
                }
                else
                {
                    $order_payment = new OrderPayment;
                    $order_payment->order_id = $order->id;
                    $order_payment->response = json_encode($response);
                    $order_payment->method='WebPays plus';
                    $order_payment->status=0;
                    $order_payment->save();
                    
                    return redirect()->route('orders-summary',$order->order_code);
                }
            }else{
                return redirect()->route('orders-summary',$order->order_code);
            }
        } catch (\Exception $e) {
            return response()->json(['message'=>'payment not found!'.$e], 404);
        }
	}
	
	function resume_order_payment($order_code)
	{
	    $order = Order::where('order_code',$order_code)->first();
	    try {
    	    $order_payment_status=0;
            //dd($_POST);

            /** Token de la transacción */
            $token = filter_input(INPUT_POST, 'token_ws');
            
            $request = array(
                "token" => filter_input(INPUT_POST, 'token_ws')
            );
            
            $data='';
    		$method='PUT';
    		$endpoint='/rswebpaytransaction/api/webpay/v1.0/transactions/'.$token;
    		
            $response = $this->get_ws($data,$method,"sandbox",$endpoint);
            //dd($response);

            if(isset($response->response_code)){
               $order->status= ($response->response_code == 0 ? 1:0);
               $order->payment_status= ($response->response_code == 0 ? 1:0);
               $order->payment_detail=$response->status;
               $order->payment_method_id=2;
               $order->save();
               $order_payment_status=($response->response_code == 0 ? 1:0);
               if($order->payment_status==1){
                   $order->orderItems = OrderItem::where('order_id',$order->id)->get();
                }
            }
            
            $order_payment = new OrderPayment;
            $order_payment->order_id = $order->id;
            $order_payment->response = json_encode($response);
            $order_payment->method='WebPays plus';
            $order_payment->status=$order_payment_status;
            $order_payment->save();
                    
    	    return redirect()->route('orders-summary',$order->order_code);
        } catch (\Exception $e) {
            dd($e);
            //return response()->json(['message'=>'payment summary not found!'], 404);
            //return redirect()->route('orders-summary',$order->order_code);
        }
	}
	
	/*Mailings*/
    public function sendOrderConfirmationMail($order)
    {   
        try{
            Mail::to($order->email)->send(new OrderMail($order));
        } catch (\Exception $e) {
            return 0;
        }            
    }
}
