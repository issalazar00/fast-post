<?php

namespace App\Http\Controllers;

use App\Models\KitProduct;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use PhpParser\Node\Expr\Cast\Array_;

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
	public function index(Request $request)
	{
		$products = Product::select()
			->where('state', 1);

		if ($request->product != '') {
			$products = $products
				->where('barcode', 'LIKE', "%$request->product%")
				->orWhere('product', 'LIKE', "%$request->product%");
		}
		if ($request->category_id != '' && $request->category_id  != null && $request->category_id  != 0) {
			$products = $products
				->where('category_id', "$request->category_id");
		}
		if ($request->brand_id != '' && $request->brand_id  != null && $request->brand_id  != 0) {
			$products = $products
				->where('brand_id', "$request->brand_id");
		}

		$products = $products->orderBy('product', 'asc')->paginate(10);


		return response()->json([
			'status' => 'success',
			'code' => 200,
			'products' => $products,
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		abort(404);
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

		$validate = Validator::make($new_product, [
			'category_id' => 'required|integer|exists:categories,id',
			'tax_id' => 'required|integer|exists:taxes,id',
			'brand_id' => 'nullable|integer|exists:brands,id',
			'product' => 'required|string|min:3|max:100',
			'barcode' => 'required|numeric|unique:products',
			'type' => 'required|integer',
			'cost_price_tax_exc' => 'required|numeric',
			'cost_price_tax_inc' => 'required|numeric',
			'gain' => 'required|numeric',
			'sale_price_tax_exc' => 'required|numeric',
			'sale_price_tax_inc' => 'required|numeric',
			'wholesale_price_tax_exc' => 'required|numeric',
			'wholesale_price_tax_inc' => 'required|numeric',
			'stock' => 'required|boolean',
			'quantity' => 'nullable|numeric',
			'minimum' => 'nullable|numeric',
			'maximum' => 'nullable|numeric'
		]);

		if (!$validate->fails()) {
			$product = new Product();
			$product->barcode = $new_product['barcode'];
			$product->product = $new_product['product'];
			$product->type = $new_product['type'];
			$product->cost_price_tax_exc = $new_product['cost_price_tax_exc'];
			$product->cost_price_tax_inc = $new_product['cost_price_tax_inc'];
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

			$data = [
				'status' => 'success',
				'code' => 200,
				'message' => 'Registro exitoso',
				'product' => $product
			];
		} else {
			$data = [
				'status' => 'error',
				'code' =>  400,
				'message' => 'Validación de datos incorrecta',
				'errors' =>  $validate->errors()
			];
		}

		return response()->json($data, $data['code']);
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
		abort(404);
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
		$p = $request->product;
		$product = Product::find($id);

		$validate = Validator::make($p, [
			'category_id' => 'required|integer|exists:categories,id',
			'tax_id' => 'required|integer|exists:taxes,id',
			'brand_id' => 'nullable|integer|exists:brands,id',
			'product' => 'required|string|min:3|max:100',
			'barcode' => ['required', 'numeric', Rule::unique('products')->ignore($product->barcode, 'barcode')],
			'type' => 'required|integer',
			'cost_price_tax_exc' => 'required|numeric',
			'cost_price_tax_inc' => 'required|numeric',
			'gain' => 'required|numeric',
			'sale_price_tax_exc' => 'required|numeric',
			'sale_price_tax_inc' => 'required|numeric',
			'wholesale_price_tax_exc' => 'required|numeric',
			'wholesale_price_tax_inc' => 'required|numeric',
			'stock' => 'required|boolean',
			'quantity' => 'nullable|numeric',
			'minimum' => 'nullable|numeric',
			'maximum' => 'nullable|numeric'
		]);

		if (!$validate->fails()) {

			$product->barcode = $p['barcode'];
			$product->product = $p['product'];
			$product->type = $p['type'];
			$product->cost_price_tax_exc = $p['cost_price_tax_exc'];
			$product->cost_price_tax_inc = $p['cost_price_tax_inc'];
			$product->gain = $p['gain'];
			$product->sale_price_tax_exc = $p['sale_price_tax_exc'];
			$product->sale_price_tax_inc = $p['sale_price_tax_inc'];
			$product->wholesale_price_tax_exc = $p['wholesale_price_tax_exc'];
			$product->wholesale_price_tax_inc = $p['wholesale_price_tax_inc'];
			$product->stock = $p['stock'];
			$product->quantity = $p['quantity'];
			$product->minimum = $p['minimum'];
			$product->maximum = $p['maximum'];
			$product->category_id = $p['category_id'];
			$product->tax_id = $p['tax_id'];
			$product->brand_id = $p['brand_id'];
			$product->save();

			if ($p['type'] == 3) {
				if (count($request->itemListKit) > 0) {
					foreach ($request->itemListKit as $item) {

						KitProduct::updateOrCreate(
							['product_parent_id' => $id, 'product_child_id' => $item['product_id']],
							[
								'quantity' => $item['quantity'],
								'product' => $item['product'],
								'barcode' => $item['barcode']
							]
						);
					}
				}
			}

			$data = [
				'status' => 'success',
				'code' => 200,
				'message' => 'Actualización exitoso',
				'product' => $product
			];
		} else {
			$data = [
				'status' => 'error',
				'code' =>  400,
				'message' => 'Validación de datos incorrecta',
				'errors' =>  $validate->errors()
			];
		}

		return response()->json($data, $data['code']);
	}

	public function updateStockByBarcode($type, $barcode, $quantity)
	{
		$product = Product::select('id', 'barcode', 'quantity')->where('barcode', $barcode)->first();
		if ($type == 1) {
			$product->quantity = $product->quantity - $quantity;
		}
		if ($type == 2) {
			$product->quantity = $product->quantity + $quantity;
		}
		$product->save();
	}

	public function updateStockById(Request $request, $id)
	{
		$product = Product::findOrFail($id);
		$product->quantity = $product->quantity + $request->quantity;
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
		abort(404);
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
			->where('barcode', 'LIKE', "$request->product%")
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
				// ->limit(5)
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

	public function updatePriceById($id, $cost_price_tax_inc)
	{
		$product = Product::select('id', 'cost_price_tax_inc')->where('id', $id)->first();
		$product->cost_price_tax_inc  =  $cost_price_tax_inc;
		$product->save();
	}
}
