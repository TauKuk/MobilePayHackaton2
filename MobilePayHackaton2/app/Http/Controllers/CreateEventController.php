<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Models\ChallengeActivity;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use DateTime;

class CreateEventController extends Controller
{
    public function index() {       
        $challenges = Challenge::all();
        return Inertia::render('Home', compact("challenges"));        
    }

    public function show($eventID) {
        $challenge = Challenge::where('id', $eventID)->get();     
        $users = User::all();
        $stravaID = session("stravaID");

        $usernames = [];
        $distances = [];

        if (count(ChallengeActivity::where('stravaID', session("stravaID"))->where('challengeID', $eventID)->get()) != 0) $distance = $this->findDistance($eventID, session("stravaID"));
        else $distance = 0;

        foreach ($users as $user) {
            if (count(ChallengeActivity::where('stravaID', $user->stravaID)->where('challengeID', $eventID)->get()) != 0) {
                array_push($usernames, $user->username);
            }

            if (count(ChallengeActivity::where('stravaID', $user->stravaID)->where('challengeID', $eventID)->get()) != 0) {
                $challengeActivity = ChallengeActivity::where('stravaID', $user->stravaID)->where('challengeID', $eventID)->get()->first();
                $challengeCheck = $challenge->first();
                if (!$challengeCheck->hasEnded) array_push($distances, $this->findDistance($eventID, $user->stravaID));
                else array_push($distances, $challengeActivity->finalDistance);
            }
        }

        $hasJoined = true;
        if (count(ChallengeActivity::where('stravaID', $stravaID)->where('challengeID', $eventID)->get()) == 0) $hasJoined=false;

        return Inertia::render('Event', compact("challenge", 'stravaID', 'distance', 'usernames', 'distances', 'hasJoined'));           
    }

    public function askDelete($eventID) {     
        $stravaID = session('stravaID');
        $challenge = Challenge::where('id', $eventID)->get();

        if ($challenge[0]->stravaID != $stravaID) return redirect()->route('home');

        return Inertia::render('AskDelete', compact("challenge", 'stravaID'));        
    }

    public function update($eventID) {  
        $stravaID = session('stravaID');
        $challenge = Challenge::where('id', $eventID)->get();

        if ($challenge[0]->stravaID != $stravaID) return redirect()->route('home');

        return Inertia::render('Update', compact("challenge", 'stravaID'));        
    }

    public function storeUpdate(Request $request) {       
        $data = $this->validateCreate($request);

        Challenge::where('id', $request->id)->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'max_score' => $data['max_score'],
            'type' => $data['type'],
            'total_distance_km' => $data['total_distance_km'],
            'stravaID' => session('stravaID'),
            'hasEnded' => $data['hasEnded'],
        ]);

        if ($request->hasEnded) {
            $this->updateChallengesFromStrava();

            $users = User::all();

            foreach ($users as $user) {
                if (count(ChallengeActivity::where('stravaID', $user->stravaID)->where('challengeID', $request->id)->get()) != 0) {
                    $final_distance = $this->findDistance($request->id, $user->stravaID);
                    ChallengeActivity::where("stravaID", $user->stravaID)->where("challengeID", $request->id)->update([
                        "finalDistance" => $final_distance,
                    ]); 
                }  
            }
        }

        $challenges = Challenge::all();
        return Inertia::render('Home', compact("challenges"));        
    }

    function updateChallengesFromStrava() {

        $users =  User::whereNotNull("authenticationToken")->get();
        foreach ($users as $user){
            $access_token = $user->authenticationToken;
            $SID = $user->stravaID;

            $athleteStats= $this->getAthleteStats($SID,$access_token);
            $totalDistance = $athleteStats["all_run_totals"]["distance"];
            $start_time = date('Y-m-d H:i:s');
            $dt = new DateTime($start_time);
            $iso_date_time = $dt->format(DateTime::ISO8601);
            $dist = 0;
            $challenges = Challenge::where('hasEnded', true)
            ->get();

            foreach ($challenges as $challenge) {
                    $res = $this->findChallengeActivity($user['stravaID'] , $challenge['id']);
                    if ($res['uploadedToStrava'] == null) continue;
                    if($res['uploadedToStrava']) continue;
                    // Use the Strava API to create a new activity
                    $response = Http::withToken($access_token)->post('https://www.strava.com/api/v3/activities', [
                    'type' => $challenge->activity_type,
                    'start_date_local' => $iso_date_time,
                    'elapsed_time' => $dist,
                    'total_distance_km' => $challenge->$totalDistance-$res['total_distance_km']*1000,
                ]);
                ChallengeActivity::where('stravaID',  $user['stravaID'])->where('challengeID', $challenge['stravaID'])->update([
                    'uploadedToStrava' => true,
                ]);
            }
        }
    }   

    private function findChallengeActivity($stravaID, $eventID) {
        $user = ChallengeActivity::where('stravaID',$stravaID)
                    ->where('challengeID',$eventID)
                    ->first();
        return $user;
    }

    private function getAthleteStats($athlete_id, $access_token) {
        $response = Http::withOptions(['verify' => false])->withHeaders([
            'Authorization' => 'Bearer ' . $access_token,
        ])->get('https://www.strava.com/api/v3/athletes/' . $athlete_id . '/stats');
        // dd($access_token);
        return $response->json();
    }

    public function destroy(Request $request) { 
        // dd($request);      
        $challenge = Challenge::find($request->id);
        $challenge->delete();

        $challenges = Challenge::all();
        return Inertia::render('Home', compact("challenges"));    
    }

    public function store(Request $request) {    
        $request['hasEnded'] = false;
        $data = $this->validateCreate($request);

        $authentication_token = User::where('stravaID', session('stravaID'))->get()->first();
            if (!$authentication_token) {            
                $authentication_token = null;
            }        
        else $authentication_token = $authentication_token->authenticationToken;

        $response = $this->getAthleteStats(session('stravaID'), $authentication_token);

        $tempDistance=$response["all_run_totals"]["distance"];
        $tempDistance = round($tempDistance/1000, 2);

        $challenge = Challenge::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'max_score' => $data['max_score'],
            'type' => $data['type'],
            'total_distance_km' => $data['total_distance_km'],
            'stravaID' => session('stravaID'),
            'hasEnded' => 0,
        ]);

        $challengeActivity = ChallengeActivity::create([
            'stravaID' => session('stravaID'),
            
            'challengeID' => $challenge->id,
            'startingDistance' => $tempDistance ,
        ]);

        $challenge->save();
        $challengeActivity->save();

        $challenges = Challenge::all();
        return Inertia::render('Home', compact("challenges"));        
    }

    private function findDistance($eventID, $stravaID) {
        // function getAthleteStats($athlete_id, $access_token) {
        //     $response = Http::withOptions(['verify' => false])->withHeaders([
        //         'Authorization' => 'Bearer ' . $access_token,
        //     ])->get('https://www.strava.com/api/v3/athletes/' . $athlete_id . '/stats');
        
        //     return $response->json();
        // }
        // function getAccessToken($stravaID) {
        //     $user = User::where('stravaID', $stravaID)->get()->first();
        //     if (!$user) {            
        //         return null;
        //     }        
        //     return $user->authenticationToken;
        // }
        // function findChallengeActivity($stravaID, $eventID) {
        //     $user = ChallengeActivity::where('stravaID',$stravaID)
        //                 ->where('challengeID',$eventID)
        //                 ->first();
        //     return $user;
        // }

        $authentication_token = User::where('stravaID', $stravaID)->get()->first();
        if (!$authentication_token) {            
            $authentication_token = null;
        }        
        else $authentication_token = $authentication_token->authenticationToken;

        $response = Http::withOptions(['verify' => false])->withHeaders([
            'Authorization' => 'Bearer ' . $authentication_token,
        ])->get('https://www.strava.com/api/v3/athletes/' . $stravaID . '/stats')->json();

        // $res = getAthleteStats($stravaID, $authentication_token);

        $tempDistance=$response["all_run_totals"]["distance"];
        $res2 = ChallengeActivity::where('stravaID',$stravaID)->where('challengeID',$eventID)->first();
        // dd($res2->startingDistance);
        $res2 = $res2->startingDistance;

        $distance = round($tempDistance/1000, 2);
        $distance =  $distance - $res2;

        return $distance;
    }
    
    private function validateCreate(Request $request) {
        return $request->validate([
            'name' => ['required', 'string', 'max:50', 'min:2'],
            'description' => ['nullable', 'string', 'max:50'],
            'max_score' => ['required', 'integer', "min:1"],
            'type' => ['required', 'string'],
            'total_distance_km' => ['required', 'numeric', "min: 0.1"],
            'hasEnded' => ['required']
        ]);          
    }
}
