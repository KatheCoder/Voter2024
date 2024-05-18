<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RespondentController;
use App\Http\Controllers\UserController;
use App\Models\Models\UploadRecord;
use App\Models\Respondent;
use Illuminate\Support\Facades\Route;

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

// Login routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {


    Route::get('/admin', function () {
        return view('admin');
    })->name('admin');

    Route::post('/import', [RespondentController::class, 'import']);

    // Catch-all route for Vue SPA
    Route::get('/{vue_capture?}',[UserController::class,'index'])
        ->where('vue_capture', '[\/\w\.-]*');

});
