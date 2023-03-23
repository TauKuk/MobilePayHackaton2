<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CreateEventController;
use App\Models\User;
use Illuminate\Support\Facades\Http;
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

Route::get('/exchange_token', function (Illuminate\Http\Request $request) {
    $params = [
        'client_id' => env('STRAVA_CLIENT_ID'),
        'client_secret' => env('STRAVA_CLIENT_SECRET'),
        'code' => $request->input('code'),
        'grant_type' => 'authorization_code',
    ];
    // dd($params);

    $response = Http::asForm()->withOptions(['verify' => false])->post('https://www.strava.com/oauth/token', $params);
    $data = $response->json();

    // dd($data);
    $access_token = $data['access_token'];
    $username = $data['athlete']['username'];
    $id = $data['athlete']['id'];

    $user = User::create([
        'username' => $username,
        'stravaID' => $id,
        'authenticationToken' => $access_token,
    ]);

    $user->save();

    // TODO: Store the access token or use it to make API calls to Strava
    // $response = Http::withToken($access_token)->get('https://www.strava.com/api/v3/athlete%27);
    // $data = $response->json();

    return redirect('/home');
});

// Route::middleware('auth')->group(function () {
//     Route::get('/home', function () {
//         return Inertia::render('Home');
//     });
// });

require __DIR__.'/auth.php';
