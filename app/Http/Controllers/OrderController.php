<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:order.index')->only('index');
        $this->middleware('can:order.store')->only('store');
        $this->middleware('can:order.update')->only('update');
        $this->middleware('can:order.delete')->only('destroy');
        $this->middleware('can:order.active')->only('active');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'orders' => Order::paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id =  Auth::user()->id;
        
        $order = new Order;
        $order->client_id = $request->id_client;
        $order->user_id = $user_id;
        $order->no_invoice = Str::random(10);
        $order->total_paid = $request->total_tax_inc;
        $order->total_iva_inc = $request->total_tax_inc;
        $order->total_iva_exc = $request->total_tax_exc;
        $order->total_discount = $request->total_discount;
        $order->state = '1';
        $order->save();

        $details_order = new DetailOrderController;
        $details_order = $details_order->store($request, $order->id);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        // var_dump($order);
        $details  = Order::find($order->id);
        return $details->detailOrders()->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
