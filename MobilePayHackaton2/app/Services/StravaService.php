<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\User;

class StravaService
{
    public static function getTotalDistanceByUser() {
        function getAthleteStats($athlete_id, $access_token) {
            $response = Http::withOptions(['verify' => false])->withHeaders([
                'Authorization' => 'Bearer ' . $access_token,
            ])->get('https://www.strava.com/api/v3/athletes/' . $athlete_id . '/stats');
        
            return $response->json();
        }
        // Get all users from the database
        $users = User::all();

        // Loop through all users and get their total distance
        foreach ($users as $user) {
            // Get the user's access token from the database or session
            try{
                $access_token = $user->authenticationToken;
                $athlete_id = $user->stravaID;
    
                $response = getAthleteStats($athlete_id, $access_token) ;
    
                dd($response);
                
                $run_distance= $response['all_run_totals'];
                $ride_distance= $response['all_ride_totals'];
    
            }
            catch (\Exception $e) {
                dd("Error: ". $e);
            }
            

            // Sum up the total distance of all activities for the user

        }
    }


}