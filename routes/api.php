<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CarController;
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

Route::post('/customers', [AuthController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function () {
  Route::apiResource('customers', AuthController::class)->except(['store']);
  Route::apiResource('cars', CarController::class);
  Route::get('/customers/{id}/cars', [AuthController::class, 'show']);
});
