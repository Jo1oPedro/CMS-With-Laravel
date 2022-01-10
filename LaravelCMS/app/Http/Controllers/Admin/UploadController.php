<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    public function imageupload(Request $request) {

        $request->validate([
            'file' => 'required|image|mimes:jpeg,jpg,png'
        ]);

        /*
            para não ocorrer de duas imagens terem nomes iguais
            e uma acabar substituindo a outra, o nome que a ima
            gem vai receber, vai ser baseado no tempo que ela foi 
            criado
        */
        /*
            $ext = $request->file->extension();
            $imageNome = time().'.'.$request->file->extension();
        */
        $imageName = time().'.'.$request->file->extension();

        $request->file->move(public_path('media/images'), $imageName);

        return [
            'location' => asset('media/images/'.$imageName)
        ];

    }
}
