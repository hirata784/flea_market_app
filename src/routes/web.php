<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ExhibitController;
use App\Http\Controllers\EditController;
use App\Http\Controllers\TransactionController;


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

Route::get('/', [ListController::class, 'index']);
Route::get('/search', [ListController::class, 'indexSearch']);
Route::get('/item/:{item_id}', [DetailController::class, 'index']);

//ログインかつメール認証済みのみアクセス可能
Route::group(['middleware' => 'auth', 'middleware' => 'verified'], function () {
    Route::get('/item/:{item_id}/add_like', [DetailController::class, 'addLike']);
    Route::get('/item/:{item_id}/delete_like', [DetailController::class, 'deleteLike']);
    Route::post('/add_comment', [DetailController::class, 'addComment']);

    Route::get('/purchase/:{item_id}', [PurchaseController::class, 'index']);
    Route::post('/purchase/success', [PurchaseController::class, 'success'])->name('purchase.success');

    Route::get('/purchase/address/:{item_id}', [AddressController::class, 'index']);
    Route::post('/purchase/address/:{item_id}/update
    ', [AddressController::class, 'update']);

    Route::get('/sell', [ExhibitController::class, 'index']);
    Route::post('/sell/add', [ExhibitController::class, 'add']);

    Route::get('/mypage', [ProfileController::class, 'index']);

    Route::get('/mypage/profile', [EditController::class, 'index']);
    Route::post('/mypage/profile/update', [EditController::class, 'update']);

    Route::get('/re_verified', [ProfileController::class, 're_verified']);

    Route::get('/transaction/:{item_id}', [TransactionController::class, 'index']);
});
