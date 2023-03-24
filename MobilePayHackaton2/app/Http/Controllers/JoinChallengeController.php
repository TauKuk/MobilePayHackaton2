<?php

namespace App\Http\Controllers;

use App\Models\ChallengeActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class JoinChallengeController extends Controller
{
    public function index($eventID) {  
        function getAthleteStats($athlete_id, $access_token) {
            $response = Http::withOptions(['verify' => false])->withHeaders([
                'Authorization' => 'Bearer ' . $access_token,
            ])->get('https://www.strava.com/api/v3/athletes/' . $athlete_id . '/stats');
        
            return $response->json();
        }
        function getAccessToken($stravaID) {
            $user = User::find($stravaID);
            if (!$user) {
                return null;
            }
            return $user->access_token;
        }

        $res = getAthleteStats(session('stravaID'),getAccessToken(session('stravaID')));
        $distace = round($res["all_run_totals"]["distance"]/1000, 2);

        $challengeActivity = ChallengeActivity::create([
            'stravaID' => session('stravaID'),
            'challengeID' => $eventID,
            'startingDistance' => $distace,
        ]);

        $challengeActivity->save();

        return redirect()->route('home');
    }
}