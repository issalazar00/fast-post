<?php

namespace App\Http\Controllers;

use App\Models\DetailOrder;
use Illuminate\Http\Request;

class DetailOrderController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
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
	public function store(Request $request, $order_id)
	{
		foreach ($request->productsOrder as $value) {
			var_dump($value);
			$detail = new DetailOrder;
			$detail->order_id = $order_id;
			$detail->product_id = $value['product_id'];
			$detail->barcode = $value['barcode'];
			$detail->discount_percentage = $value['discount_percentage'];
			$detail->discount_price = $value['discount_price'];
			$detail->price_tax_exc = $value['price_tax_exc'];
			$detail->price_tax_inc = $value['price_tax_inc'];
			$detail->quantity = $value['qty'];
			$detail->product = $value['product'];
			$detail->save();
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\DetailOrder  $detailOrder
	 * @return \Illuminate\Http\Response
	 */
	public function show(DetailOrder $detailOrder)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\DetailOrder  $detailOrder
	 * @return \Illuminate\Http\Response
	 */
	public function edit(DetailOrder $detailOrder)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\DetailOrder  $detailOrder
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, DetailOrder $detailOrder)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\DetailOrder  $detailOrder
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(DetailOrder $detailOrder)
	{
		//
	}
}
