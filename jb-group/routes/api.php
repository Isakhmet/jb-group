<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('parser')->group(function () {
    Route::post('accesses', [\App\Http\Controllers\ParserController::class, 'accesses']);
    Route::post('branches', [\App\Http\Controllers\ParserController::class, 'branches']);
    Route::post('currencies', [\App\Http\Controllers\ParserController::class, 'currencies']);
    Route::post('employees', [\App\Http\Controllers\ParserController::class, 'employees']);
    Route::post('branches-currencies', [\App\Http\Controllers\ParserController::class, 'branchesCurrencies']);
    Route::post('users', [\App\Http\Controllers\ParserController::class, 'users']);
    Route::post('user-roles', [\App\Http\Controllers\ParserController::class, 'userRoles']);
    Route::post('user-branches', [\App\Http\Controllers\ParserController::class, 'userBranches']);


    Route::post('get-users', [\App\Http\Controllers\ParserController::class, 'parseUsers']);
    Route::post('get-accesses', [\App\Http\Controllers\ParserController::class, 'parseRoleAccesses']);
    Route::post('get-currencies', [\App\Http\Controllers\ParserController::class, 'parseCurrency']);
    Route::post('get-employees', [\App\Http\Controllers\ParserController::class, 'parseEmployee']);
    Route::post('get-branch', [\App\Http\Controllers\ParserController::class, 'parseBranches']);
    Route::post('get-branch-currencies', [\App\Http\Controllers\ParserController::class, 'parseBranchCurrency']);
    Route::post('get-users-roles', [\App\Http\Controllers\ParserController::class, 'parseUsersRoles']);
});
