<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        
        $loggedId = Auth::id();

        $user = User::find($loggedId);

        if($user) {
            return view('admin.profile.index', [
                'user' => $user
            ]);
        }

        return redirect()->route('admin');

    }

    public function save(Request $request) {

        $user = User::find(Auth::id());
        
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
                    'name' => ['string', 'max:100'],
                    'email' => ['string', 'email', 'max:100']
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
                        // 2.2 Verificamos se o novo email já existe
                        $hasEmail = User::where('email', $data['email'])->get();
                        // 2.3 Se não existir, nós alteramos
                        if(count($hasEmail) === 0) {
                            $user->email = $data['email'];
                        } else {
                            if((count($hasEmail) == 1 && $user->email != $data['email']) || count($hasEmail) > 1) {
                                $validator->errors()->add('email', __('validation.unique', [
                                    'attribute' => 'email'
                                ]));
                            } 
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
                return redirect()->route('profile')->withErrors('É necessario preencher o campo nome, email ou senha para poder editar');
            }
            
            if(isset($validator)) {
                if(count($validator->errors()) > 0) {
                    return redirect()->route('profile')->withErrors($validator);
                }
            }

            $user->save();
            return redirect()->route('profile')->with('success', 'Usuário editado com sucesso');
        }
        return redirect()->route('profile')->withErrors('Usuário não encontrado');
    }
}
