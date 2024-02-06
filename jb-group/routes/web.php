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
        return redirect('branch-currency');
    });
    Route::resource('currencies', \App\Http\Controllers\CurrencyController::class);
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('roles', \App\Http\Controllers\RoleController::class);
    Route::resource('branches', \App\Http\Controllers\BranchController::class);
    Route::resource('accesses', \App\Http\Controllers\AccessController::class);
    Route::resource('branch-currency', \App\Http\Controllers\BranchCurrencyController::class);
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);
    Route::resource('clients', \App\Http\Controllers\ClientController::class);
    Route::resource('organizations', \App\Http\Controllers\OrganizationController::class);
    Route::resource('medias', \App\Http\Controllers\MediaController::class);
    Route::resource('purchasing', \App\Http\Controllers\PurchasingRequestsController::class);
    Route::get('purchasing-all', [\App\Http\Controllers\PurchasingRequestsController::class, 'allList'])->name('purchasing-all');
    Route::resource('product-type-directory', \App\Http\Controllers\ProductTypeDirectory::class);
    Route::resource('product-directory', \App\Http\Controllers\ProductDirectory::class);
    Route::get('/branch-currency-edit', [\App\Http\Controllers\BranchCurrencyController::class, 'edit']);
    Route::get('/branch-currency-delete', [\App\Http\Controllers\BranchCurrencyController::class, 'delete']);
    Route::get('/get-branch-currency', [\App\Http\Controllers\BranchCurrencyController::class, 'getBalance']);
    Route::get('/get-balance-by-currency', [\App\Http\Controllers\BranchCurrencyController::class, 'getBalanceByCurrency']);
    Route::post('/update-branch-currency', [\App\Http\Controllers\BranchCurrencyController::class, 'update']);
    Route::resource('events', \App\Http\Controllers\NewsController::class);
    Route::get('upload-files', [\App\Http\Controllers\UploadController::class, 'upload'])->name('upload-files');
    Route::get('show-news', [\App\Http\Controllers\NewsController::class, 'showNews'])->name('show-news');

    Route::get('add-branch', [\App\Http\Controllers\UserController::class, 'addBranch']);
    Route::post('bind-branch', [\App\Http\Controllers\UserController::class, 'bindBranch']);
    Route::get('list-branch', [\App\Http\Controllers\UserController::class, 'listBranch']);
    Route::post('destroy-branch/{id}', [\App\Http\Controllers\UserController::class, 'destroyBranch']);

    Route::get('schedule', [\App\Http\Controllers\ScheduleEmployeeController::class, 'index'])->name('schedule');
    Route::post('schedule-save', [\App\Http\Controllers\ScheduleEmployeeController::class, 'save'])->name('schedule-save');
    Route::get('notify', [\App\Http\Controllers\NotifyController::class, 'notify']);
});

Route::post('admin-login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('admin-login');
Route::post('upload', [\App\Http\Controllers\MediaController::class, 'store'] )->name('upload');
Route::get('create-album', [\App\Http\Controllers\MediaController::class, 'createAlbum'] )->name('create-album');
Route::get('remove-album', [\App\Http\Controllers\MediaController::class, 'removeAlbum'] )->name('remove-album');
Route::get('gallery', [\App\Http\Controllers\MediaController::class, 'show'] )->name('gallery');
Route::get('remove/{file}', [\App\Http\Controllers\MediaController::class, 'destroy'] )->name('remove');
Route::get('deleteByOne', [\App\Http\Controllers\MediaController::class, 'deleteByOne'] )->name('deleteByOne');
Route::post('webhook/{handler}', [\App\Http\Controllers\Test\TelegramController::class, 'main']);
Route::get('test', [\App\Http\Controllers\Test\TelegramController::class, 'test']);

Auth::routes();
