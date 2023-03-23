<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CreateEventController;


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

Route::get('/', function () {
    return Inertia::render('App');
});
Route::get('/home', [CreateEventController::class, 'index']);

Route::get('/event/create', function () {
    return Inertia::render('EventCreate');
});
Route::post('/event/create', [CreateEventController::class, 'store'])->name("eventCreate");

Route::get('/event/delete/{eventID}', [CreateEventController::class, 'askDelete']);
Route::post('/event/deleteTrue', [CreateEventController::class, 'destroy'])->name('trueDelete');

Route::get('/event/update/{eventID}', [CreateEventController::class, 'update']);
Route::post('/event/update', [CreateEventController::class, 'storeUpdate'])->name('updateEvent');

Route::get('/events/{eventID}', [CreateEventController::class, 'show']);

// Route::middleware('auth')->group(function () {
//     Route::get('/home', function () {
//         return Inertia::render('Home');
//     });
// });

require __DIR__.'/auth.php';
