<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Models\Barang;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//barang
Route::get('list/barang', [BarangController::class, 'index']);
Route::get('get-barang', [BarangController::class, 'get_data']);
Route::post('store-barang', [BarangController::class, 'store_barang']);
Route::put('get-barang/{id}', [BarangController::class, 'get_detail']);
Route::delete('hapus-barang/{id}', [BarangController::class, 'hapus_barang']);
Route::get('get-master-barang-paging', [BarangController::class, 'get_barang_paging']);