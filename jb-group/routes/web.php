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
    Route::resource('accesses', \App\Http\Controllers\AccessController::class);
    Route::resource('branch-currency', \App\Http\Controllers\BranchCurrencyController::class);
    Route::get('/branch-currency-edit', [\App\Http\Controllers\BranchCurrencyController::class, 'edit']);
    Route::get('/get-branch-currency', [\App\Http\Controllers\BranchCurrencyController::class, 'getBalance']);
    Route::post('/update-branch-currency', [\App\Http\Controllers\BranchCurrencyController::class, 'update']);

    Route::get('add-branch', [\App\Http\Controllers\UserController::class, 'addBranch']);
    Route::post('bind-branch', [\App\Http\Controllers\UserController::class, 'bindBranch']);
    Route::get('list-branch', [\App\Http\Controllers\UserController::class, 'listBranch']);
    Route::post('destroy-branch/{id}', [\App\Http\Controllers\UserController::class, 'destroyBranch']);
});

Route::post('admin-login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('admin-login');

Auth::routes();
