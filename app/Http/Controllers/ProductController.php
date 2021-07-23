<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $products = new Product;
        $products = $products
            ->select('products.*', 'categories.name as category')
            ->leftJoin('categories', 'categories.id', 'products.category_id')
            ->orderBy('barcode', 'asc')
            ->paginate(15)
        ;


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
    public function show($id)
    {
        //
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
        $product->sale_price = $request['sale_price'];
        $product->wholesale_price = $request['wholesale_price'];
        $product->stock = $request['stock'];
        $product->quantity = $request['quantity'];
        $product->minimum = $request['minimum'];
        $product->maximum = $request['maximum'];
        $product->category_id = $request['category_id'];
        $product->tax_id = $request['tax_id'];
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
}
