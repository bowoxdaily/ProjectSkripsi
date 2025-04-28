<?php

use App\Http\Controllers\Be\AuthControllerBE;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthControllerBE::class, 'register']);
Route::post('/login', [AuthControllerBE::class, 'login']);
Route::post('/logout', [AuthControllerBE::class, 'logout']);
Route::post('/check-field', [AuthControllerBE::class, 'checkField']);
