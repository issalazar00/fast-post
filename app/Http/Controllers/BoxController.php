<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\ConsecutiveBox;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BoxController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:category.index')->only('index', 'show');
        $this->middleware('can:category.store')->only('store');
        $this->middleware('can:category.update')->only('update');
        $this->middleware('can:category.delete')->only('destroy');
        $this->middleware('can:category.active')->only('active');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'boxes' => Box::paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'name' => 'required|alpha_num|min:1|max:10',
            'prefix' => 'required|alpha_num|min:1|max:10',
            'consecutive_box' => 'nullable|array',
            'consecutive_box.*.from_nro' => 'integer',
            'consecutive_box.*.until_nro' => 'integer',
            'consecutive_box.*.from_date' => 'date',
            'consecutive_box.*.until_date' => 'date|after:consecutive_box.*.from_date'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'code' =>  400,
                'message' => 'Validación de datos incorrecta',
                'errors' =>  $validate->errors()
            ], 400);
        }

        $box = Box::create([
            'name' => $request->name,
            'prefix' => $request->prefix
        ]);

        foreach($request->consecutive_box as $item){
            ConsecutiveBox::create([
                'box_id' => $box->id,
                'from_nro'=> $item['from_nro'], 
                'until_nro'=> $item['until_nro'], 
                'from_date'=> $item['from_date'], 
                'until_date'=> $item['until_date']
            ]);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Registro exitoso',
            'box' => $box
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $box = Box::find($id);

        if ($box) {
            $data = [
                'status' => 'success',
                'code' => 200,
                'box' => $box
            ];
        } else {
            $data = [
                'status' => 'error',
                'code' => 400,
                'message' => 'Registro no encontrado'
            ];
        }

        return response()->json($data, $data['code']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return abort(404);
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
        $validate = Validator::make($request->all(), [
            'name' => 'required|alpha_num|min:1|max:10',
            'prefix' => 'required|alpha_num|min:1|max:10',
            'consecutive_box' => 'nullable|array',
            'consecutive_box.*.from_nro' => 'integer',
            'consecutive_box.*.until_nro' => 'integer',
            'consecutive_box.*.from_date' => 'date|',
            'consecutive_box.*.until_date' => 'date|after:consecutive_box.*.from_date',
            'consecutive_load' => 'nullable|array',
            'consecutive_load.*.from_nro' => 'integer',
            'consecutive_load.*.until_nro' => 'integer',
            'consecutive_load.*.from_date' => 'date',
            'consecutive_load.*.until_date' => 'date'
        ]);
        
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'code' =>  400,
                'message' => 'Validación de datos incorrecta',
                'errors' =>  $validate->errors()
            ], 400);
        }

        $box = Box::find($id);

        if ($box) {
            
            if(!$box->process){
                $box->prefix =  $request->prefix ;
            }
            $box->name = $request->name;
            $box->update();

            $used = [];
            foreach($request->consecutive_load as $item){
                if($item['process']){
                    $used[] = $item['id'];
                }
            }
    
            ConsecutiveBox::whereNotIn('id', $used)->where('box_id', $box->id)->delete();

            foreach($request->consecutive_box as $item){
                ConsecutiveBox::create([
                    'box_id' => $box->id,
                    'from_nro'=> $item['from_nro'], 
                    'until_nro'=> $item['until_nro'], 
                    'from_date'=> $item['from_date'], 
                    'until_date'=> $item['until_date']
                ]);
            }

            $data = [
                'status' => 'success',
                'code' =>  200,
                'message' => 'Actualización exitosa',
                'category' =>  $box
            ];
        } else {
            $data = [
                'status' => 'error',
                'code' =>  400,
                'message' => 'Registro no encontrado',
            ];
        }
        return response()->json($data, $data['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $box = Box::find($id);

        if ($box) {
            $box->delete();
            $data = [
                'status' => 'success',
                'code' => 200,
                'category' => $box
            ];
        } else {
            $data = [
                'status' => 'error',
                'code' => 400,
                'message' => 'Registro no encontrado'
            ];
        }

        return response()->json($data, $data['code']);
    }

    /**
     * Activate the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate($id)
    {
        $box = Box::find($id);
        $box->active = !$box->active;
        $box->save();
    }

    public function consecutiveAllByBox(Box $box){
        
        $consecutive = ConsecutiveBox::where('box_id', $box->id)->get();
        $consecutive = collect($consecutive);

        $consecutive->map(function($item) use ($box){
            $bill_number = $box->prefix.$item->from_nro;
            $orders = Order::where('bill_number', $bill_number)->where('box_id', $box->id)->count();

            $item->process = $orders > 0 ?  true : false;
            
            return $item;
        });
        
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'consecutive' => $consecutive
        ], 200);
    }
}
