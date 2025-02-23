<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use Symfony\Component\HttpKernel\Profiler\Profile;

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
Route::get('/tab', [ItemController::class, 'tab']);

// 認証時のみ表示
Route::middleware('auth')->group(function () {
    Route::post('/mypage', [ProfileController::class, 'mypage']);
    Route::get('/mypage/profile', [ProfileController::class, 'profile']);
    Route::get('/purchase/address/:{item_id}', [ProfileController::class, 'address']);
    Route::post('/item/:{item_id}/like', [LikeController::class, 'like']);
    Route::delete('/item/:{item_id}/unlike', [LikeController::class, 'unlike']);
    Route::post('/purchase/:{item_id}', [ItemController::class, 'purchase']);
    Route::post('/comment', [ItemController::class, 'comment']);
    Route::get('/sell', [ItemController::class, 'sell']);
    Route::post('/', [ItemController::class, 'buy']);
    Route::post('/add', [ItemController::class, 'add']);
});
