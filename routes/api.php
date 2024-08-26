<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\StopController;
use App\Http\Controllers\TravelController;
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

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/travels', [TravelController::class, 'index']);
    Route::post('/travels/store', [TravelController::class, 'store']);
    Route::patch('/travels/update/{id}', [TravelController::class, 'update']);
    Route::delete('/travels/delete/{id}', [TravelController::class, 'destroy']);

    Route::post('/stops/store', [StopController::class, 'store']);
    Route::patch('/stops/update/{id}', [StopController::class, 'update']);
    Route::delete('/stops/delete/{id}', [StopController::class, 'destroy']);

    Route::post('/images/store', [ImageController::class, 'store']);
    Route::delete('/images/delete/{id}', [ImageController::class, 'destroy']);
});
