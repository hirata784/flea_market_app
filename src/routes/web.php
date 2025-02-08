<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;


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

Route::get('/', [ItemController::class, 'index']);
Route::get('/search', [ItemController::class, 'search']);
Route::get('/item/:{item_id}', [ItemController::class, 'item']);

// 認証時のみ表示
Route::middleware('auth')->group(function () {
    Route::get('/mypage/profile', [AuthController::class, 'profile']);
    Route::get('/purchase', [ItemController::class, 'purchase']);
});
