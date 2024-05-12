<?php

use App\Http\Controllers\DataController;
use App\Http\Controllers\FilterController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/filters', [FilterController::class, 'fetchFilters']);
Route::get('/filtered-data', [DataController::class, 'getPieFilteredData']);
Route::get('/data', [DataController::class, 'getFilteredData']);
Route::get('/count', [DataController::class, 'getOverallCount']);
