<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:user.index')->only('index');
        $this->middleware('can:user.store')->only('store','register');
        $this->middleware('can:user.update')->only('update');
        $this->middleware('can:user.delete')->only('destroy');
        $this->middleware('can:user.active')->only('active');
    }

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email:rfc,dns|unique:users|max:255',
            'password' => 'required|confirmed|min:8',
            'rol' => 'required|integer|exists:roles,id'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'code' =>  400,
                'message' => 'Validación de datos incorrecta',
                'errors' =>  $validate->errors()
            ], 400);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $user->syncRoles($request->input('rol'));

        return response()->json([
            'status' => 'success',
            'code' =>  200,
            'message' => 'Usuario registrado correctamente',
            'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'code' =>  400,
                'message' => 'Validación de datos incorrecta',
                'errors' =>  $validate->errors()
            ], 400);
        }


        $user = User::where('email', $request->input('email'))->first();

        if (is_object($user) && $user->state) {
            $validatePassword = Hash::check($request->input('password'), $user->password);

            if ($validatePassword) {
                $token = Str::random(80);
                $user->api_token = hash('sha256', $token);
                $user->update();

                $data = [
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Login correcto',
                    'user' => [
                        'sub' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'permissions' => $user->getAllPermissions(),
                        'iat' => time(),
                        'exp' => time() + (7 * 60),
                        'api_token' =>  $token
                    ]
                ];
            } else {
                $data = [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Contraseña incorrecta'
                ];
            }

            
        }else{
            $data = [
                'status' => 'error',
                'code' => 400,
                'message' => 'Usuario desactivado'
            ];
        }

        return response()->json($data, $data['code']);
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
            'users' => User::paginate(10)
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
        return abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return abort(404);
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
                'name' => 'required|string|min:3|max:255',
                'rol' => 'required|integer|exists:roles,id',
                'password' => 'nullable|confirmed|min:8',
                'email' => 'required|email:rfc,dns|max:255', Rule::unique('users')->ignore($request->user()->id)

            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => 'error',
                    'code' =>  400,
                    'message' => 'Validación de datos incorrecta',
                    'errors' =>  $validate->errors()
                ], 400);
            }

            $user = User::where('id', $id)->update([
                'name' => $request->input('name'),
                'email' => $request->input('email')
            ]);

            $user = User::find($id);
            $user->syncRoles([$request->input('rol')]);

            $data = [
                'status' => 'success',
                'code' =>  200,
                'message' => 'Actualización exitosa ',
                'user' => $user
            ];

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
        return abort(404);
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
        $user = User::find($id);
        $user->state = !$user->state;
        $user->save();
    }
}
