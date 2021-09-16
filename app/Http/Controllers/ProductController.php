<?php

namespace App\Http\Controllers;

use App\Models\KitProduct;
use Illuminate\Http\Request;
use App\Models\Product;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class ProductController extends Controller
{
	public function __construct()
	{
		$this->middleware('can:product.index')->only('index');
		$this->middleware('can:product.store')->only('store');
		$this->middleware('can:product.update')->only('update');
		$this->middleware('can:product.delete')->only('destroy');
		$this->middleware('can:product.active')->only('active');
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
		$new_product = $request->product;
		$product = new Product();
		$product->barcode = $new_product['barcode'];
		$product->product = $new_product['product'];
		$product->type = $new_product['type'];
		$product->cost_price = $new_product['cost_price'];
		$product->gain = $new_product['gain'];
		$product->sale_price_tax_exc = $new_product['sale_price_tax_exc'];
		$product->sale_price_tax_inc = $new_product['sale_price_tax_inc'];
		$product->wholesale_price_tax_exc = $new_product['wholesale_price_tax_exc'];
		$product->wholesale_price_tax_inc = $new_product['wholesale_price_tax_inc'];
		$product->stock = $new_product['stock'];
		$product->quantity = $new_product['quantity'];
		$product->minimum = $new_product['minimum'];
		$product->maximum = $new_product['maximum'];
		$product->category_id = $new_product['category_id'];
		$product->tax_id = $new_product['tax_id'];
		$product->brand_id = $new_product['brand_id'];
		$product->save();

		// Se crea un kit
		if ($new_product['type'] == 3) {
			if (count($request->itemListKit) > 0) {
				foreach ($request->itemListKit as $item) {
					$kitProduct = new KitProduct();
					$kitProduct->product_parent_id = $product->id;
					$kitProduct->product_child_id = $item['product_id'];
					$kitProduct->quantity = $item['quantity'];
					$kitProduct->product = $item['product'];
					$kitProduct->barcode = $item['barcode'];
					$kitProduct->save();
				}
			}
		}
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
		$product->state = !$product->state;
		$product->save();
	}

	public function searchProduct(Request $request)
	{

		$products = Product::select()
			->where('barcode', 'LIKE', "%$request->product%")
			->orWhere('product', 'LIKE', "%$request->product%")
			->where('state', 1)
			->first();
		return ['products' => $products];
	}

	public function filterProductList(Request $request)
	{

		if (!$request->product || $request->product == '' || $request->product == NULL) {
			$products = Product::select()
				->where('state', 1)
				->limit(5)
				->get();
		} else {
			$products = Product::select()
				->where('state', 1)
				->where('barcode', 'LIKE', "%$request->product%")
				->orWhere('product', 'LIKE', "%$request->product%")
				->limit(5)
				->get();
		}


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
