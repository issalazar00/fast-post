<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\Configuration;
use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use PDF;

class OrderController extends Controller
{

	public function __construct()
	{
		$this->middleware('can:order.index')->only('index', 'generatePdf');
		$this->middleware('can:order.store')->only('store');
		$this->middleware('can:order.update')->only('update');
		$this->middleware('can:order.delete')->only('destroy');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$user_id =  Auth::user()->id;

		$orders = Order::whereHas('client', function (Builder $query) use ($request) {
			if ($request->client != '') {
				$query->where('name', 'like', "%$request->client%");
			}
		});
		if ($request->no_invoice != '') {
			$orders = $orders->where('no_invoice', 'like', "%$request->no_invoice");
		}
		$today = date('Y-m-d');
		$from = $request->from;
		$to = $request->to;

		if ($from != '') {
			$orders = $orders
				->where('created_at', '>=', $from);
		}

		if ($to != '') {
			$orders = $orders
				->where('created_at', '<=', $to);
		}

		if ($from == '' && $to == '') {
			$orders = $orders
				->where('created_at', '>=', $today);
		}

		$orders = $orders
			->where('user_id', $user_id)
			->paginate(10);

		return response()->json([
			'status' => 'success',
			'code' => 200,
			'orders' => $orders,
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

		$latestOrder = Order::where('box_id', $request->box_id)->orderByDesc('id')->first();
		$box = Box::find($request->box_id);
		$dateValidate = Carbon::now()->toDateString();

		if ($latestOrder) {

			$lastConsecutive = str_replace($box->prefix, '', $latestOrder->bill_number);
			$lastConsecutive = intval($lastConsecutive);
			

			$consecutiveBox = $box->consecutiveBox()->where([
				['from_nro', '<=', $lastConsecutive],
				['until_nro', '>=', $lastConsecutive]
			])
				->where('until_date', '>=', $dateValidate)
				->orderBy('from_nro')
				->first();

			if ($consecutiveBox && $consecutiveBox->until_nro > $lastConsecutive) {
				$bill_number = $box->prefix . ($lastConsecutive + 1);
			} else {
				$continue = $box->consecutiveBox()
					->where('until_nro', '>', $lastConsecutive)
					->where('from_date', '<=', $dateValidate)
					->where('until_date', '>=', $dateValidate)
					->where('id', '!=', isset($consecutiveBox->id) ? $consecutiveBox->id : null)
					->orderBy('from_nro')
					->first();

				if (!$continue) {
					return abort(500);
				}

				$bill_number = $box->prefix . $continue->from_nro;
			}
		} else {
			$continue = $box->consecutiveBox()
				->where('from_date', '<=', $dateValidate)
				->where('until_date', '>=', $dateValidate)
				->orderBy('from_nro')
				->first();

			if (!$continue) {
				return abort(500);
			}
			$bill_number = $box->prefix . $continue->from_nro;
		}

		$order = new Order;
		$order->client_id = $request->id_client;
		$order->user_id = $user_id;
		$order->no_invoice = strtoupper(Str::random(10));
		$order->total_paid = $request->total_tax_inc;
		$order->total_iva_inc = $request->total_tax_inc;
		$order->total_iva_exc = $request->total_tax_exc;
		$order->total_discount = $request->total_discount;
		$order->box_id = $box->id;
		$order->bill_number = $bill_number;

		if ($request->state == 4) {
			$order->state = 2;
			$order->payment_date = date('Y-m-d');
		} else {
			$order->state = $request->state;
			if ($request->state == 2) {
				$order->payment_date = date('Y-m-d');
			}
		}
		$order->save();

		foreach ($request->productsOrder as $details_order) {
			$new_detail = new DetailOrderController;
			$new_detail = $new_detail->store($details_order, $order->id);

			$update_stock = new ProductController;
			$update_stock = $update_stock->updateStockByBarcode(1, $details_order['barcode'], $details_order['quantity']);
		}
		if ($request->state == 4) {
			$print = new PrintOrderController();
			$print = $print->printTicket($order->id, $request->cash, $request->change);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function show(Order $order)
	{
		$details  = Order::find($order->id);
		return ['order_information' => $details, 'order_details' => $details->detailOrders()->get(), 'user' => $details->user()->first()];
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
	public function update(Request $request, $id)
	{

		$order = Order::find($id);
		$order->client_id = $request->id_client;
		$order->total_paid = $request->total_tax_inc;
		$order->total_iva_inc = $request->total_tax_inc;
		$order->total_iva_exc = $request->total_tax_exc;
		$order->total_discount = $request->total_discount;
		if ($request->state == 4) {
			$order->state = 2;
			$order->payment_date = date('Y-m-d');
		} else {
			$order->state = $request->state;
			if ($request->state == 2) {
				$order->payment_date = date('Y-m-d');
			}
		}

		$order->update();

		foreach ($request->productsOrder as $details_order) {

			DetailOrder::updateOrCreate(
				['order_id' => $id, 'product_id' => $details_order['product_id']],
				[
					'discount_percentage' => $details_order['discount_percentage'],
					'discount_price' => $details_order['discount_price'],
					'price_tax_exc' => $details_order['price_tax_exc'],
					'price_tax_inc' => $details_order['price_tax_inc'],
					'price_tax_inc_total' => $details_order['price_tax_inc_total'],
					'quantity' => $details_order['quantity'],
					'barcode' => $details_order['barcode'],
					'product' => $details_order['product'],
				]
			);
		}

		if ($request->state == 4) {
			$print = new PrintOrderController();
			$print = $print->printTicket($order->id, $request->cash, $request->change);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Order  $order)
	{
		$order->delete();
	}

	public function generatePdf(Order $order)
	{
		$details  = $order;
		$data = [
			'orderInformation' => $details,
			'orderDetails' => $details->detailOrders()->get(),
			'user' => $details->user()->first(),
			'configuration' => Configuration::first(),
			'url' => URL::to('/')
		];

		$pdf = PDF::loadView('templates.order', $data);

		$pdf = $pdf->download('prueba.pdf');

		$data = [
			'status' => 200,
			'pdf' => base64_encode($pdf),
			'message' => 'Orden generada en pdf'
		];

		return response()->json($data);
	}
}
