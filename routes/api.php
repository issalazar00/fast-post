<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DetailOrderController;
use App\Http\Controllers\KitProductController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ImportProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DetailBillingController;
use App\Http\Controllers\ReportController;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:api')->group(function () {
	Route::resource('/users', UserController::class);
	Route::post('/users/{user}/activate',  [UserController::class, 'activate']);
	Route::post('/register', [UserController::class, 'register']);

	Route::post('/categories/{category}/activate',  [CategoryController::class, 'activate']);
	Route::get('/categories/category-list', [CategoryController::class, 'categoryList']);
	Route::resource('/categories', CategoryController::class);

	Route::resource('/taxes', TaxController::class);
	Route::post('/taxes/{tax}/activate',  [TaxController::class, 'activate']);

	Route::post('/brands/{brand}/activate',  [BrandController::class, 'activate']);
	Route::get('/brands/brand-list', [BrandController::class, 'brandList']);
	Route::resource('/brands', BrandController::class);

	Route::get('/orders/generatePdf/{order}', [OrderController::class, 'generatePdf']);
	Route::resource('/orders',  OrderController::class);
	Route::resource('/order-details', DetailOrderController::class);
	
	Route::get('/billings/generatePdf/{billing}', [BillingController::class, 'generatePdf']);
	Route::resource('/billings',  BillingController::class);
	Route::resource('/billing-details', DetailBillingController::class);


	Route::resource('/products',  ProductController::class);
	Route::post('/products/{product}/activate',  [ProductController::class, 'activate']);
	Route::post('/products/search-product',  [ProductController::class, 'searchProduct']);
	Route::post('/products/filter-product-list',  [ProductController::class, 'filterProductList']);
	Route::post('/products/stock-update/{id}', [ProductController::class, 'updateStockById']);

	Route::resource('kit-products', KitProductController::class);

	Route::resource('/suppliers',  SupplierController::class);
	Route::post('/suppliers/{supplier}/activate',  [SupplierController::class, 'activate']);
	Route::post('/suppliers/search-supplier',  [SupplierController::class, 'searchSupplier']);
	Route::post('/suppliers/filter-supplier-list',  [SupplierController::class, 'filterSupplierList']);

	Route::resource('/clients',  ClientController::class);
	Route::post('/clients/{client}/activate',  [ClientController::class, 'activate']);
	Route::post('/clients/search-client',  [ClientController::class, 'searchClient']);
	Route::post('/clients/filter-client-list',  [ClientController::class, 'filterClientList']);


	Route::get('/roles/getAllRoles', [RoleController::class, 'getAllRoles']);
	Route::resource('/roles', RoleController::class);
	Route::get('/permissions', [RoleController::class, 'getPermissions']);

	Route::get('/departments', [DepartmentController::class, 'index']);
	Route::get('/departments/{id}/getMunicipalities', [DepartmentController::class, 'getMunicipalitiesByDepartment']);

	Route::resource('/configurations', ConfigurationController::class)->except(['create', 'edit', 'destroy', 'show']);
	Route::get('/company-logo', function () {
		$configuration = new Configuration();
		$image = $configuration->select('logo')->first();
		return $image;
	});
	Route::post('/import/upload-file-import', [ImportProductController::class, 'uploadFile']);

	Route::get('/reports/report-sales', [ReportController::class, 'reportSales']);
});
