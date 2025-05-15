<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SimpleFacturaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\Org;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Token;
use App\Models\Reading;

class DteController extends Controller
{
    protected $_param;
    public $org;
    
    public function __construct()
    {
        $this->middleware('auth');
        $id = \Route::current()->Parameter('id');
        $this->org = Org::find($id);
    }

    // Método para mostrar el voucher
    public function create($id, $order_code)
    {
        \Log::info('Buscando voucher para org: ' . $id . ', orden: ' . $order_code);
        $org = $this->org;
        // Obtener la orden basada en el código de la orden
        $order = Order::where('order_code', $order_code)->firstOrFail();

        // Obtener los artículos de la orden
        $orderItems = OrderItem::where('order_id', $order->id)->get();

        // Datos del lugar (por ejemplo, dirección de la tienda)
        $storeName = "Tienda Ejemplo";
        $storeAddress = "Dirección 123, Ciudad, País";
        
        // Datos del pago
        $paymentMethod = $order->payment_method ?? 'Efectivo';
        $totalAmount = $order->total;
        $subtotal = $order->sub_total;
        $commission = $order->commission;
        $iva = $order->iva;

       
        $response = ['status' => 'success', 'message' => 'Voucher generado correctamente']; 
        
        // Pasar los datos a la vista
        return view('orgs.vouchers.show', compact(
            'order', 'orderItems', 'paymentMethod', 'totalAmount','subtotal', 
            'commission', 'iva', 'storeName', 'storeAddress','response', 'org' ));// ¡Esto es lo que faltaba!
    }

	public function dte_create($org_id, $reading_id)
	{
	    try {
    	    $reading = Reading::find($reading_id);
    
            if($reading->total > 0 AND $reading->folio > 0){
                $token = (new SimpleFacturaController)->token($org_id);
                
                if($reading->invoice_type =='boleta'){
                    $data = $this->boleta($reading);
                }elseif($reading->invoice_type =='factura'){
                    $data = $this->factura($reading);
                }
                $endpoint="invoiceV2/Casa_Matriz";
                $method="POST";
                $response = (new SimpleFacturaController)->get_ws($data,$token,$method,"sandbox",$endpoint);
                //dd($response);
                if($response->data){
                    $reading->invoice_dte_type = $response->data->tipoDTE;
                    $reading->invoice_date = Carbon::parse($response->data->fechaEmision)->format('Y-m-d');
                    $reading->folio = $response->data->folio; 
                }
                $reading->invoice_message = $response->message;
                $reading->save();
                return response()->json(['message'=>$reading]);
            }else{
                return redirect()->route('orders-summary',$order->order_code);
            }
        } catch (\Exception $e) {
            return response()->json(['message'=>'payment not found!'.$e], 404);
        }
	}
	
	public function dte_bell($org_id, $reading_id)
	{
	    try {
    	    $reading = Reading::find($reading_id);
    
            if($reading->total > 0 AND $reading->folio > 0){
                $token = (new SimpleFacturaController)->token($org_id);
                $codeTypeDte = ($reading->invoice_type=='factura'? 33 : 41);
                $data = '{
                    "credenciales": {
                        "rutEmisor": "76269769-6"
                    },
                    "dteReferenciadoExterno": {
                        "folio": '.$reading->folio.',
                        "codigoTipoDte": '.$codeTypeDte.',
                        "ambiente": 0
                    }
                }';
                $endpoint="dte/timbre";
                $method="POST";
                $response = (new SimpleFacturaController)->get_ws($data,$token,$method,"sandbox",$endpoint);
                //dd($response);
                if($response->data){
                    $reading->invoice_bell = $response->data;
                    $reading->invoice_message = $response->message;
                    $reading->save();
                }
                return response()->json(['message'=>$reading]);
            }else{
                //return redirect()->route('orders-summary',$order->order_code);
            }
        } catch (\Exception $e) {
            return response()->json(['message'=>'payment not found!'.$e], 404);
        }
	}
	
	private function boleta($reading)
	{
        return '{
                "Documento": {
                        "Encabezado": {
                            "IdDoc": {
                                "TipoDTE": 41,
                                "FchEmis": "2023-04-21",
                                "FchVenc": "2023-05-21",
                                "IndServicio":3
                            },
                            "Emisor": {
                                "RUTEmisor": "76269769-6",
                                "RznSocEmisor": "Chilesystems",
                                "GiroEmisor": "Desarrollo de software",
                                "DirOrigen": "Calle 7 numero 3",
                                "CmnaOrigen": "Santiago"
                            },
                            "Receptor": {
                                "RUTRecep":"17246644-3",
                                "RznSocRecep": "Proveedor Test",
                                "DirRecep": "calle 12",
                                "CmnaRecep": "Paine",
                                "CiudadRecep": "Santiago"
                            },
                            "Totales": {
                                "MntExe": "990",
                                "MntTotal": "990"
                            }
                        },
                        "Detalle": [{
                            "NroLinDet": "1",
                            "NmbItem": "Alfajor",
                            "QtyItem": "1",
                            "UnmdItem": "un",
                            "PrcItem": "990",
                            "MontoItem": "990",
                            "IndExe":1
                        }]
                    }
                }';
	}
	
	private function factura($reading)
	{
        return '{
                "Documento": {
                    "Encabezado": {
                        "IdDoc": {
                            "TipoDTE": 33,
                            "FchEmis": "2023-10-19",
                            "FmaPago": 1,
                            "FchVenc": "2023-10-19"
                        },
                        "Emisor": {
                            "RUTEmisor": "76269769-6",
                            "RznSoc": "Chilesystems",
                            "GiroEmis": "Desarrollo de software",
                            "Telefono": [
                                "912345678"
                            ],
                            "CorreoEmisor": "mvega@chilesystems.com",
                            "Acteco": [
                                620200
                            ],
                            "DirOrigen": "Calle 7 numero 3",
                            "CmnaOrigen": "Santiago",
                            "CiudadOrigen": "Santiago"
                        },
                        "Receptor": {
                            "RUTRecep": "10422710-4",
                            "RznSocRecep": "McL",
                            "GiroRecep": "test",
                            "CorreoRecep": "amamani@chilesystems.com",
                            "DirRecep": "calle 12",
                            "CmnaRecep": "Paine",
                            "CiudadRecep": "Santiago"
                        },
                        "Transporte": {
                            "DirDest": "Los Pensamientos 2307",
                            "CmnaDest": "Providencia",
                            "CiudadDest": "Santiago"
                        },
                        "Totales": {
                            "MntNeto": "84",
                            "TasaIVA": "19",
                            "IVA": "16",
                            "MntTotal": "100"
                        }
                    },
                    "Detalle": [
                        {
                            "NroLinDet": "1",
                            "CdgItem": [
                                {
                                    "TpoCodigo": "ALFA",
                                    "VlrCodigo": "123"
                                }
                            ],
                            "NmbItem": "Producto Test",
                            "QtyItem": "1",
                            "UnmdItem": "un",
                            "PrcItem": "100",
                            "MontoItem": "100"
                        }
                    ],
                    "Referencia": [
                        {
                            "NroLinRef": 1,
                            "TpoDocRef": "802",
                            "FolioRef": "1121121",
                            "FchRef": "2023-09-07",
                            "RazonRef": "Razon de referencia"
                        }
                    ],
                    "DscRcgGlobal": [
                        {
                            "NroLinDR": "1",
                            "GlosaDR": "Descuento Global Test",
                            "TpoMov": 1,
                            "TpoValor": 2,
                            "ValorDR": "16"
                        }
                    ]
                },
                "Observaciones": "NOTA AL PIE DE PAGINA",
                "TipoPago": "30 dias"
            }';
	}
}

