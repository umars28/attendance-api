<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EpresenceController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/epresence', [EpresenceController::class, 'index']);
    Route::post('/epresence', [EpresenceController::class, 'store']);
    Route::patch('/epresence/{id}/approve', [EpresenceController::class, 'approve']);
});
