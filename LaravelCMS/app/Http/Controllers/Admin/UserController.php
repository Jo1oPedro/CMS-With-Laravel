<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('can:edit-users');
    }

    public function index()
    {
        //$users = User::all();

        $loggedId = Auth::id();
        $users = User::paginate(10);
        return view('admin.users.index', [
            'users' => $users,
            'loggedId' => $loggedId
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'name',
            'email',
            'password',
            'password_confirmation'
        ]);

        $validator = Validator::make($data, [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:200|unique:users',
            'password' => 'required|string|min:4|confirmed'
            //'password' => ['required', 'string', 'min:4', 'confirmed'] é possível também;
        ]);

        if($validator->fails()) {
            return redirect()->route('users.create')
            ->withErrors($validator)
            ->withInput();
        }

        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso');
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
        $user = User::find($id);

        if($user) {
            return view('admin.users.edit', [
                'user' => $user
            ]);
        }
     
        return redirect('users.index');
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
        $user = User::find($id);

        if($user) {

            $data = $request->only( [
                'name',
                'email',
                'password',
                'password_confirmation'
            ]);
            if(!empty($data['name']) && !empty($data['email'])) {
                $validator = Validator::make([
                    'name' => $data['name'],
                    'email' => $data['email'],
                ], [
                    'name' => ['required', 'string', 'max:100'],
                    'email' => ['required', 'string', 'email', 'max:100']
                ]);
            } else if(!empty($data['name'])) {
                $validator = Validator::make([
                    'name' => $data['name'],
                ], [
                    'name' => ['required', 'string', 'max:100'],
                ]);
            } else if(!empty($data['email'])) {
                $validator = Validator::make([
                    'email' =>$data['email'],
                ], [
                    'email' => ['required', 'string', 'email','max:100']
                ]); 
            }

            if(!empty($data['name']) || !empty($data['email'] || !empty($data['password']))) {
                // 1. Alteração do nome
                if(!empty($data['name'])) {
                    $user->name = $data['name'];
                }
                // 2. Alteração do email
                // 2.1 Primeiro, verificamos se o email foi alterado
    
                if(!empty($data['email']))
                {
                    if($user->email != $data['email']) {
                        // 2.2 Verificamos se o novo email já existe
                        $hasEmail = User::where('email', $data['email'])->get();
                        // 2.3 Se não existir, nós alteramos
                        if(count($hasEmail) === 0) {
                            $user->email = $data['email'];
                        } else {
                            $validator->errors()->add('email', __('validation.unique', [
                                'attribute' => 'email'
                            ]));
                        }
                    } else {
                        $validator->errors()->add('email', __('validation.unique', [
                            'attribute' => 'email'
                        ]));
                    }
                }
                // 3. Alteração de senha
                // 3.1 Verifica se o usuário digitou senha
                if(!empty($data['password'])) {
                    // 3.2 Verifica se a confirmação está ok
                    if(strlen($data['password']) >= 4) {
                        if($data['password'] === $data['password_confirmation']) {
                            // 3.3 Altera a senha
                            $user->password = Hash::make($data['password']);
                        } else {
                            $validator->errors()->add('password', __('validation.confirmed', [
                                'attribute' => 'password'
                            ]));
                        }
                    } else{
                        $validator->errors()->add('password', __('validation.min.string', [
                            'attribute' => 'password',
                            'min' => 4
                        ]));
                    }
                }
            } else {
                return redirect()->route('users.edit', [
                    'user' => $id
                ])->withErrors('É necessario preencher o campo nome, email ou senha para poder editar');
            }
            
            if(isset($validator)) {
                if(count($validator->errors()) > 0) {
                    return redirect()->route('users.edit', [
                        'user' => $id
                    ])->withErrors($validator);
                }
            }

            $user->save();
        }

        return redirect()->route('users.index')->with('success', 'Usuário editado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loggedId = Auth::id();

        if($loggedId != $id) {
            $user = User::find($id);
            $user->delete();
            return redirect()->route('users.index')->with('success', 'Usuário deletado com sucesso');
        }

        return redirect()->route('users.index');
    }
}
