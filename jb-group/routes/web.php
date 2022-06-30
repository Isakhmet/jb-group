<?php

use Illuminate\Support\Facades\Route;

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

Route::middleware('auth')->group(function(){
    Route::get('/', function () {
        return view('home');
    });
    Route::resource('currencies', \App\Http\Controllers\CurrencyController::class);
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('roles', \App\Http\Controllers\RoleController::class);
    Route::resource('branches', \App\Http\Controllers\BranchController::class);
    Route::resource('branch-currency', \App\Http\Controllers\BranchCurrencyController::class);
});

Route::post('admin-login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('admin-login');

Auth::routes();
