<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Credit;
use App\Models\DetailCredit;
use App\Models\PaymentCredit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PDF;

class CreditController extends Controller
{
	public function __construct()
	{
		$this->middleware('can:credit.index')->only('index', 'generatePdf');
		$this->middleware('can:credit.store')->only('store','creditByClient','payCreditByClient');
		$this->middleware('can:credit.update')->only('update');
		$this->middleware('can:credit.delete')->only('destroy');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$user_id =  Auth::user()->id;

		$credits = Credit::whereHas('client', function (Builder $query) use ($request) {
			if ($request->client != '') {
				$query->where('name', 'like', "%$request->client%");
			}
		});
		if ($request->no_invoice != '') {
			$credits = $credits->where('no_invoice', 'like', "%$request->no_invoice");
		}
		$today = date('Y-m-d');
		$from = $request->from;
		$to = $request->to;

		if ($from != '' && $from != 'undefined') {
			$credits = $credits
				->where('created_at', '>=', $from);
		}

		if ($to != '' && $to != 'undefined') {
			$credits = $credits
				->where('created_at', '<=', $to);
		}

		if ($from == '' && $to == '') {
			$credits = $credits
				->where('created_at', '>=', $today);
		}

		$credits = $credits
			->where('user_id', $user_id)
			->paginate(10);

		return response()->json([
			'status' => 'success',
			'code' => 200,
			'credits' => $credits,
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

		$credit = new Credit;
		$credit->client_id = $request->id_client;
		$credit->user_id = $user_id;
		$credit->no_invoice = strtoupper(Str::random(10));
		$credit->total_paid = $request->total_tax_inc;
		$credit->total_iva_inc = $request->total_tax_inc;
		$credit->total_iva_exc = $request->total_tax_exc;
		$credit->total_discount = $request->total_discount;
		$credit->state = $request->state;
		$credit->save();

		if ($request->pay_payment > 0) {
			PaymentCredit::create([
				'user_id' => $user_id,
				'credit_id' => $credit->id,
				'pay' => $request->pay_payment
			]);
		}

		foreach ($request->productsCredit as $details_credit) {
			$new_detail = new DetailCreditController;
			$new_detail = $new_detail->store($details_credit, $credit->id);

			$update_stock = new ProductController;
			$update_stock = $update_stock->updateStockByBarcode(2, $details_credit['barcode'], $details_credit['quantity']);


			if ($request->state == 4) {
				$print = new PrintOrderController();
				$print = $print->printTicket($credit->id, $request->cash, $request->change);
			}
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Credit $credit)
	{
		$details  = $credit;
		return ['credit_information' => $details, 'credit_details' => $details->detailCredits()->get(), 'user' => $details->user()->first()];
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$user_id =  Auth::user()->id;

		$credit = Credit::find($id);
		$credit->client_id = $request->id_client;
		$credit->total_paid = $request->total_tax_inc;
		$credit->total_iva_inc = $request->total_tax_inc;
		$credit->total_iva_exc = $request->total_tax_exc;
		$credit->total_discount = $request->total_discount;
		$credit->state = $request->state;
		$credit->update();

		if ($request->pay_payment > 0) {

			$paymentCredit = $credit->paymentCredits->first();
			if ($paymentCredit) {
				$paymentCredit->pay = $request->pay_payment;
				$paymentCredit->update();
			} else {
				PaymentCredit::create([
					'user_id' => $user_id,
					'credit_id' => $credit->id,
					'pay' => $request->pay_payment
				]);
			}
		}

		foreach ($request->productsCredit as $details_credit) {

			DetailCredit::updateOrCreate(
				['credit_id' => $id, 'product_id' => $details_credit['product_id']],
				[
					'discount_percentage' => $details_credit['discount_percentage'],
					'discount_price' => $details_credit['discount_price'],
					'price_tax_exc' => $details_credit['price_tax_exc'],
					'price_tax_inc' => $details_credit['price_tax_inc'],
					'price_tax_inc_total' => $details_credit['price_tax_inc_total'],
					'quantity' => $details_credit['quantity'],
					'barcode' => $details_credit['barcode'],
					'product' => $details_credit['product'],
				]
			);
		}

		if ($request->state == 4) {
			$print = new PrintOrderController();
			$print = $print->printTicket($credit->id, $request->cash, $request->change);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Credit  $credit)
	{
		$credit->delete();
	}

	public function generatePdf(Credit $credit)
	{
		$details  = $credit;
		$data = [
			'creditInformation' => $details,
			'creditDetails' => $details->detailCredits()->get(),
			'user' => $details->user()->first(),
			'configuration' => Configuration::first(),
			'url' => URL::to('/')
		];

		$pdf = PDF::loadView('templates.credit', $data);

		$pdf = $pdf->download('template.pdf');

		$data = [
			'status' => 200,
			'pdf' => base64_encode($pdf),
			'message' => 'Crédito generada en pdf'
		];

		return response()->json($data);
	}

	public function creditByClient($clientId)
	{
		$credits = Credit::where('client_id', $clientId)->where('state', 2)->get();

		return response()->json([
			'status' => 'success',
			'code' => 200,
			'credits' => $credits,
		]);
	}

	public function payCreditByClient(Request $request)
	{
		$validate = Validator::make($request->all(), [
			'id_client' => 'required|integer|exists:clients,id',
			'pay_payment' =>  'required|numeric|min:1'
		]);

		if ($validate->fails()) {
			return response()->json([
				'status' => 'error',
				'code' => 404,
				'message' => 'Validación de datos incorrecta',
				'errors' => $validate->errors(),
			]);
		}
		$credits = DB::table('credits as c')
			->leftJoin('payment_credits as pc', 'pc.credit_id', '=', 'c.id')
			->select('c.id', 'c.total_paid', DB::raw('SUM(pc.pay) as  paid_payment'))
			->where('c.client_id', $request->id_client)
			->where('c.state', 2)
			->groupByRaw('id, total_paid')
			->orderBy('c.created_at')
			->get();

		if ($request->pay_payment > $credits->sum('total_paid')) {
			return response()->json([
				'status' => 'error',
				'code' => 400,
				'message' => 'Validación de pago incorrecta',
			]);
		}

		$user_id =  Auth::user()->id;

		foreach($credits as $credit){
			if($request->pay_payment > 0 ){

				$pending = $credit->total_paid - $credit->paid_payment;

				if($pending > $request->pay_payment)
				{
					PaymentCredit::create([
						'user_id' => $user_id,
						'credit_id' => $credit->id,
						'pay' => $request->pay_payment
					]);
					$request->pay_payment = 0;
				}else{
					PaymentCredit::create([
						'user_id' => $user_id,
						'credit_id' => $credit->id,
						'pay' => $pending
					]);

					Credit::where('id', $credit->id)->update([
						'state' => 3
					]);

					$request->pay_payment = $request->pay_payment - $pending;
				}
			}
		}

		return response()->json([
			'status' => 'success',
			'code' => 200,
			'message' => 'Abonos realizados correctamente',
		]);
	}
}
