<?php

use App\Http\Controllers\Be\AuthControllerBE;
use App\Http\Controllers\Fe\auth\AuthController;
use App\Http\Controllers\Fe\SiswaController;
use Illuminate\Support\Facades\Route;

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
    return view('dashboard');
});

Route::get('/login', [AuthController::class, 'pagelogin'])->name('viewlogin');
Route::get('/register', [AuthController::class, 'pageregister'])->name('viewregister');
Route::post('/logout', [AuthControllerBE::class, 'logout'])->name('logout');
Route::get('siswa', [SiswaController::class, 'siswapage'])->name('siswa.dashboard');
