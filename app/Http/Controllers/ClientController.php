<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
	public function __construct()
	{
		$this->middleware('can:client.index')->only('index');
		$this->middleware('can:client.store')->only('store');
		$this->middleware('can:client.update')->only('update');
		$this->middleware('can:client.delete')->only('destroy');
		$this->middleware('can:client.active')->only('activate');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$clients = new Client;
		$clients = $clients
			->paginate(15);

		return response()->json([
			'status' => 'success',
			'code' => 200,
			'clients' => $clients
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
		//

		$client = $request->all();
		Client::create($client);
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
		$client = Client::find($id);
		$client = $request->input();
		$client->save();
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
		$client = Client::find($id);
		$client->active = !$client->active;
		$client->save();
	}

	public function searchClient(Request $request)
	{

		$client = Client::select()
			->where('active', 1)
			->where('document', 'LIKE', "%$request->client%")
			->orWhere('name', 'LIKE', "%$request->client%")
			->first();

		return $client;
	}

	/**
	 * Find a Client with name or document
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */

	public function filterClientList(Request $request)
	{
		$clients = Client::select()
			->where('active', 1)
			->where('document', 'LIKE', "%$request->client%")
			->orWhere('name', 'LIKE', "%$request->client%")
			->get(20);
		return $clients;
	}
}
