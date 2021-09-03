<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
	public function __construct()
	{
		$this->middleware('can:product.index')->only('index');
		$this->middleware('can:product.store')->only('store');
		$this->middleware('can:product.update')->only('update');
		$this->middleware('can:product.delete')->only('destroy');
		$this->middleware('can:product.active')->only('active');
		$this->middleware('can:product.deactivate')->only('deactivate');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$products = Product::select()->orderBy('product', 'asc')->paginate(10);

		return response()->json([
			'status' => 'success',
			'code' => 200,
			'products' => $products,
		]);
		// return Product::orderBy('barcode', 'asc')->paginate(15);
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
		$product = $request->all();
		Product::create($product);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Product $product)
	{
		return $product;
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
		//
		$product = Product::find($id);
		$product->barcode = $request['barcode'];
		$product->product = $request['product'];
		$product->type = $request['type'];
		$product->cost_price = $request['cost_price'];
		$product->gain = $request['gain'];
		$product->sale_price_tax_exc = $request['sale_price_tax_exc'];
		$product->sale_price_tax_inc = $request['sale_price_tax_inc'];
		$product->wholesale_price_tax_exc = $request['wholesale_price_tax_exc'];
		$product->wholesale_price_tax_inc = $request['wholesale_price_tax_inc'];
		$product->stock = $request['stock'];
		$product->quantity = $request['quantity'];
		$product->minimum = $request['minimum'];
		$product->maximum = $request['maximum'];
		$product->category_id = $request['category_id'];
		$product->tax_id = $request['tax_id'];
		$product->brand_id = $request['brand_id'];
		$product->save();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}

	/**
	 * Activate the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function activate($id)
	{
		//
		$product = Product::find($id);
		$product->state = '1';
		$product->save();
	}

	/**
	 * Deactivate the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function deactivate($id)
	{
		$product = Product::find($id);
		$product->state = '0';
		$product->save();
	}

	public function searchProduct(Request $request)
	{

		$products = Product::select()
			->where('barcode', 'LIKE', $request->product)
			->orWhere('product', 'LIKE', $request->product)
			->where('state', 1)
			->first();

		return ['products' => $products];
	}

	public function filterProductList(Request $request)
	{
		$products = Product::select()
			->where('state', 1)
			->where('barcode', 'LIKE', "%$request->product%")
			->orWhere('product', 'LIKE', "%$request->product%")
			->get(20);
		return $products;
	}
	public function ajaxProcessuploadCsv()
	{
		$filename_prefix = date('YmdHis') . '-';

		if (isset($_FILES['file']) && !empty($_FILES['file']['error'])) {
			switch ($_FILES['file']['error']) {
				case UPLOAD_ERR_INI_SIZE:
					$_FILES['file']['error'] = $this->trans('The uploaded file exceeds the upload_max_filesize directive in php.ini. If your server configuration allows it, you may add a directive in your .htaccess.', [], 'Admin.Advparameters.Notification');

					break;
				case UPLOAD_ERR_FORM_SIZE:
					$_FILES['file']['error'] = $this->trans('The uploaded file exceeds the post_max_size directive in php.ini. If your server configuration allows it, you may add a directive in your .htaccess, for example:', [], 'Admin.Advparameters.Notification')
						. '<br/><a href="' . $this->context->link->getAdminLink('AdminMeta') . '" >
					<code>php_value post_max_size 20M</code> ' .
						$this->trans('(click to open "Generators" page)', [], 'Admin.Advparameters.Notification') . '</a>';

					break;

					break;
				case UPLOAD_ERR_PARTIAL:
					$_FILES['file']['error'] = $this->trans('The uploaded file was only partially uploaded.', [], 'Admin.Advparameters.Notification');

					break;

					break;
				case UPLOAD_ERR_NO_FILE:
					$_FILES['file']['error'] = $this->trans('No file was uploaded.', [], 'Admin.Advparameters.Notification');

					break;

					break;
			}
		} elseif (!preg_match('#([^\.]*?)\.(csv|xls[xt]?|o[dt]s)$#is', $_FILES['file']['name'])) {
			$_FILES['file']['error'] = $this->trans('The extension of your file should be .csv.', [], 'Admin.Advparameters.Notification');
		} elseif (
			!@filemtime($_FILES['file']['tmp_name'])
			// || !@move_uploaded_file($_FILES['file']['tmp_name'], AdminImportController::getPath() . $filename_prefix . str_replace("\0", '', $_FILES['file']['name']))
		) {
			$_FILES['file']['error'] = $this->trans('An error occurred while uploading / copying the file.', [], 'Admin.Advparameters.Notification');
		} else {
			// @chmod(AdminImportController::getPath() . $filename_prefix . $_FILES['file']['name'], 0664);
			$_FILES['file']['filename'] = $filename_prefix . str_replace('\0', '', $_FILES['file']['name']);
		}

		die(json_encode($_FILES));
	}
}
