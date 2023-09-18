<?php

namespace App\Http\Controllers;

use App\Models\Kardex;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KardexController extends Controller
{
    const NRO_RESULTS = 15;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $from = $request->from;
        $to = $request->to;
        $product = $request->product;
        $category = $request->category;
        $nro_results = $request->nro_results ?: self::NRO_RESULTS;

        $productData = null;
        if($request->filled('product') && $product != 'undefined'){
            $productData = Product::where('id', 'LIKE', "$product")
            ->orWhere('product', 'LIKE', "%$product%")
            ->firstOrFail();
        }

        $detail_kardex = Kardex::select()
            ->where(function ($query) use ($from, $to) {
                if ($from != '' && $from != 'undefined' && $from != null) {
                    $from = Carbon::parse($from)->toDateTimeString();
                    $query->where('created_at', '>=', $from);
                }
                if ($to != '' && $to != 'undefined' && $to != null) {
					$to = Carbon::parse($to)->addSeconds(59)->toDateTimeString();
					$query->where('created_at', '<=', $to);
				}
            })
            ->whereHas('product', function ($query) use ($category, $product, $request) {
                if ($request->filled('category') && $category != 'undefined') {
                    $query->where('category_id', $category);
                }

                if ($request->filled('product') && $product != 'undefined') {
                    $query->where('id', 'LIKE', "$product")
                        ->orWhere('product', 'LIKE', "%$product%");
                }
            })
            ->orderBy('id','desc')
            ->paginate($nro_results);


        return response()->json([
            'status' => 'success',
            'code' => 200,
            'kardexes' => $detail_kardex,
            'product_data' => $productData
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'quantity' => 'required|numeric'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'code' =>  400,
                'message' => 'Validación de datos incorrecta',
                'errors' =>  $validate->errors()
            ], 400);
        }

        $kardex = Kardex::create([
            'quantity' => $request->input('quantity'),
            'description' => $request->input('description'),
            'product_id' => $request->input('product_id'),
            'user_id' =>          Auth::user()->id,
            'date' => Carbon::now()
        ]);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Registro exitoso',
            'kardex' => $kardex
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeKardex($product_id, $type, $quantity, $description)
    {

        $kardex = Kardex::create([
            'quantity' => $quantity,
            'type' => $type,
            'description' => $description,
            'product_id' => $product_id,
            'user_id' => Auth::user()->id,
            'date' => Carbon::now()
        ]);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Registro exitoso',
            'kardex' => $kardex
        ], 200);
    }
}
