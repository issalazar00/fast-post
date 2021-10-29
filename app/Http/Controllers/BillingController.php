<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Billing;
use App\Models\Configuration;
use App\Models\DetailBilling;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use PDF;

class BillingController extends Controller
{

	public function __construct()
	{
		$this->middleware('can:billing.index')->only('index', 'generatePdf');
		$this->middleware('can:billing.store')->only('store');
		$this->middleware('can:billing.update')->only('update');
		$this->middleware('can:billing.delete')->only('destroy');
		$this->middleware('can:billing.active')->only('active');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$user_id =  Auth::user()->id;

		$billings = Billing::whereHas('supplier', function (Builder $query) use ($request) {
			if ($request->supplier != '') {
				$query->where('name', 'like', "%$request->supplier%");
			}
		});
		if ($request->no_invoice != '') {
			$billings = $billings->where('no_invoice', 'like', "%$request->no_invoice");
		}
		$today = date('Y-m-d');

		$billings = $billings
			->where('created_at', '>=', $today)
			->where('user_id', $user_id)
			->paginate(10);

		return response()->json([
			'status' => 'success',
			'code' => 200,
			'billings' => $billings,
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

		$billing = new Billing;
		$billing->supplier_id = $request->id_supplier;
		$billing->user_id = $user_id;
		$billing->no_invoice = strtoupper(Str::random(10));
		$billing->total_paid = $request->total_tax_inc;
		$billing->total_iva_inc = $request->total_tax_inc;
		$billing->total_iva_exc = $request->total_tax_exc;
		$billing->total_discount = $request->total_discount;
		$billing->state = $request->state;
		$billing->save();

		foreach ($request->productsBilling as $details_billing) {
			$new_detail = new DetailBillingController;
			$new_detail = $new_detail->store($details_billing, $billing->id);

			$update_stock = new ProductController;
			$update_stock = $update_stock->updateStockByBarcode(1, $details_billing['barcode'], $details_billing['quantity']);
		}

		$print = new PrintOrderController();
		$print = $print->printTicket($billing->id, $request->cash, $request->change);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Billing  $billing
	 * @return \Illuminate\Http\Response
	 */
	public function show(Billing $billing)
	{
		$details  = $billing;
		return ['billing_information' => $details, 'billing_details' => $details->detailBillings()->get(), 'user' => $details->user()->first()];
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Billing  $billing
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Billing $billing)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Billing  $billing
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{

		$billing = Billing::find($id);
		$billing->client_id = $request->id_client;
		$billing->total_paid = $request->total_tax_inc;
		$billing->total_iva_inc = $request->total_tax_inc;
		$billing->total_iva_exc = $request->total_tax_exc;
		$billing->total_discount = $request->total_discount;
		$billing->state = $request->state;
		$billing->update();

		foreach ($request->productsBilling as $details_billing) {

			DetailBilling::updateOrCreate(
				['order_id' => $id, 'product_id' => $details_billing['product_id']],
				[
					'discount_percentage' => $details_billing['discount_percentage'],
					'discount_price' => $details_billing['discount_price'],
					'price_tax_exc' => $details_billing['price_tax_exc'],
					'price_tax_inc' => $details_billing['price_tax_inc'],
					'price_tax_inc_total' => $details_billing['price_tax_inc_total'],
					'quantity' => $details_billing['quantity'],
					'barcode' => $details_billing['barcode'],
					'product' => $details_billing['product'],
				]
			);
		}

		$print = new PrintOrderController();
		$print = $print->printTicket($billing->id, $request->cash, $request->change);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Billing  $billing
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Billing  $billing)
	{
		$billing->delete();
	}

	public function generatePdf(Billing $billing)
	{
		$details  = $billing;
		$data = [
			'billingInformation' => $details,
			'billingDetails' => $details->detailBillings()->get(),
			'user' => $details->user()->first(),
			'configuration' => Configuration::first(),
			'url' => URL::to('/')
		];

		$pdf = PDF::loadView('templates.billing', $data);

		$pdf = $pdf->download('prueba.pdf');

		$data = [
			'status' => 200,
			'pdf' => base64_encode($pdf),
			'message' => 'Orden generada en pdf'
		];

		return response()->json($data);
	}
}
