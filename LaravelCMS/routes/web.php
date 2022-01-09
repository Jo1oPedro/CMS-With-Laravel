<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
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

//Route::get('/', [HomeController::class, 'index']);
Route::get('/', [LoginController::class, 'index']);

//Auth::routes();

//Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::prefix('admin')->group(function() {

    Route::get('/', [AdminHomeController::class, 'index'])->name('admin');
    // chama a login caso o usuario não esteja verificado
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'authenticate']);
    
    Route::get('register', [RegisterController::class, 'index'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    //Route::get('users', [UserController::class, 'index'])->name('users');
    Route::resource('users', UserController::class);

    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('profilesave', [ProfileController::class, 'save'])->name('profile.save');

    Route::get('settings', [SettingController::class, 'index'])->name('settings');

    Route::put('settingssave', [SettingController::class, 'save'])->name('settings.save');
});