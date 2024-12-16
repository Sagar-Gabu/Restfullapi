<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DailyActionPointController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/action/create', [DailyActionPointController::class, 'store']);
Route::get('/action/list', [DailyActionPointController::class, 'allActionPoint']);
Route::get('/action/{action}', [DailyActionPointController::class, 'getActionPointByid']);
Route::post('/action/update/{id}', [DailyActionPointController::class, 'updateActionPoint']);
Route::get('/action/{action}', [DailyActionPointController::class, 'getActionPointByid']);
Route::delete('/action/delete/{id}', [DailyActionPointController::class, 'destroy']);
