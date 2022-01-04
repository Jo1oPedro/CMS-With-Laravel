<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminHomeController extends Controller
{
    
    public function __construct() {
        $this->middleware('auth');
        //se o usuario não está logado ele chama a rota com name login
        // essa rota está na web.php
    }

    public function index() {
        return view('admin.home');
    }

}
