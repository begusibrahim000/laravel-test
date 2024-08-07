<?php

use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Auth;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

// v1
// use App\Http\Controllers\AdminController;

// use App\Http\Controllers\AbsensiController;
// use App\Http\Controllers\PegawaiController;
// use App\Http\Controllers\GajiController;
// use App\Http\Controllers\HariKerjaController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

// Route::group(['middleware' => ['auth', 'admin']], function () {
//     Route::resource('pegawai', PegawaiController::class);
//     Route::resource('gaji', GajiController::class);
//     Route::resource('hari-kerja', HariKerjaController::class);
// });

// Route::resource('absensi', AbsensiController::class)->only(['index', 'store']);

// v2
use App\Http\Controllers\AdminController;
// use App\Http\Controllers\PegawaiController;
// use App\Http\Controllers\GajiController;
// use App\Http\Controllers\HariKerjaController;
// use App\Http\Controllers\AbsensiController

// Route untuk halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Auth routes untuk login, registrasi, dll.
Auth::routes();

// Route untuk admin dashboard
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

// Middleware admin untuk route berikut
Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::resource('pegawai', PegawaiController::class);
    Route::resource('gaji', GajiController::class);
    Route::resource('hari-kerja', HariKerjaController::class);
    Route::get('laporan/rekap', [GajiController::class, 'laporanRekap'])->name('laporan.rekap');
});

// Route untuk absensi tanpa login
Route::resource('absensi', AbsensiController::class)->only(['index', 'store']);