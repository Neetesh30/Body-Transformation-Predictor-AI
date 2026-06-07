<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;


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

Route::get('/find-doctor', [DoctorController::class, 'index']);
Route::post('/get-states', [DoctorController::class, 'getStates']);
Route::post('/get-cities', [DoctorController::class, 'getCities']);
Route::post('/get-doctors', [DoctorController::class, 'getDoctors']);
Route::post('/search-doctor', [DoctorController::class, 'search'])->name('search.doctor');

Route::post('/get-doctor-video', [DoctorController::class, 'getDoctorVideo']);

Route::post('/calculate-bmi', [DoctorController::class, 'store'])->name('calculate.bmi');
Route::get('/mudras', [App\Http\Controllers\DoctorController::class, 'mudras'])->name('mudras');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


