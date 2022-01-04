<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index']);

//Auth::routes();

//Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::prefix('admin')->group(function() {

    Route::get('/', [AdminHomeController::class, 'index'])->name('admin');
    Route::get('login', [LoginController::class, 'index'])->name('login');
    // chama a login caso o usuario não esteja verificado
});