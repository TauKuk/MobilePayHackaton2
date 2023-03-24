<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CreateEventController;
use App\Http\Controllers\JoinChallengeController;
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
    if (session('stravaID') != null) { return redirect('/home'); }
    return Inertia::render('App');
})->name('welcome');

Route::middleware(['session'])->group(function () {
    Route::get('/home', [CreateEventController::class, 'index'])->name('home');

    Route::get('/event/create', function () {
        return Inertia::render('EventCreate');
    });
    
    Route::post('/event/create', [CreateEventController::class, 'store'])->name("eventCreate");
    
    Route::get('/event/delete/{eventID}', [CreateEventController::class, 'askDelete']);
    Route::post('/event/deleteTrue', [CreateEventController::class, 'destroy'])->name('trueDelete');
    
    Route::get('/event/update/{eventID}', [CreateEventController::class, 'update']);
    Route::post('/event/update', [CreateEventController::class, 'storeUpdate'])->name('updateEvent');
    
    Route::get('/events/{eventID}', [CreateEventController::class, 'show']);

    Route::get('/event/{eventID}/join', [JoinChallengeController::class, 'index']);
});

Route::get('/exchange_token', function (Illuminate\Http\Request $request) {
    $params = [
        'client_id' => env('STRAVA_CLIENT_ID'),
        'client_secret' => env('STRAVA_CLIENT_SECRET'),
        'code' => $request->input('code'),
        'grant_type' => 'authorization_code',
    ];

    $response = Http::asForm()->withOptions(['verify' => false])->post('https://www.strava.com/oauth/token', $params);
    $data = $response->json();

    if (!isset($data['access_token'])) return redirect('/');

    // dd($data);
    $access_token = $data['access_token'];
    $username = $data['athlete']['username'];
    $id = $data['athlete']['id'];

    // dd(count(User::where('stravaID', $id)->get()));

    if (count(User::where('stravaID', $id)->get()) != 0) {
        session(['stravaID' => $id, 'authenticationToken' => $access_token, 'username' => $username]);

        return redirect()->route('home');
    } 
    
    $user = User::Create([
        'username' => $username,
        'stravaID' => $id,
        'authenticationToken' => $access_token,
    ]);
    session(['stravaID' => $id, 'authenticationToken' => $access_token, 'username' => $username]);

    $user->save();

    return redirect('/home');
});

Route::post('/logout', function() {
    session()->forget('stravaID');
    session()->forget('username');
    session()->forget('authenticationToken');

    return redirect()->route('welcome');
})->name('logout');

// require __DIR__.'/auth.php';
