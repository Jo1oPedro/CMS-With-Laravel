<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {

        $settings = [];

        $dbsettings = Setting::get();

        foreach($dbsettings as $dbsetting) {
            $settings[ $dbsetting['name'] ] = $dbsetting['content'];
            //$dbsettings['name'] 'name' equivale ao nome do campo, por exemplo: email, bgcolor e etc...
            //$dbsettings['name'] 'name' equivale ao conteudo do campo, por exemplo: cascata@gmail, #000000 e etc...
        }

        return view('admin.settings.index', [
            'settings' => $settings
        ]);

    }

    public function save(Request $request) {
        $data = $request->only(
            ['title', 'subtitle', 'email', 'bgcolor', 'textcolor']
        );

        $validator = $this->validator($data);
        
        if($validator->fails()) {
            return redirect()->route('settings')
            ->withErrors($validator);
        }

        foreach($data as $item => $value) {

            Setting::where('name', $item)->update([
                'content' => $value
            ]);

        }

        return redirect()->route('settings')->with('success', 'Alterações realizadas com sucesso');

    }   

    protected function validator($data) {
        return Validator::make($data, [
            'title' => ['string', 'max:100'],
            'subtitle' => ['string', 'max:100'],
            'email' => ['string', 'email'],
            'bgcolor' => ['string', 'regex:/#[A-Z0-9]{6}/i'],
            'textcolor' => ['string', 'regex:/#[A-Z0-9]{6}/i']
        ]);
    }

}
