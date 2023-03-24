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
        $stravaID = session('stravaID');

        return Inertia::render('Event', compact("challenge", 'stravaID'));           
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
            'hasEnded' => $data['hasEnded']
        ]);

        $challenges = Challenge::all();
        return Inertia::render('Home', compact("challenges"));        
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
            'startingDistance' => 0,
        ]);

        $challenge->save();
        $challengeActivity->save();

        $challenges = Challenge::all();
        return Inertia::render('Home', compact("challenges"));        
    }

    function updateChallengesFromStrava() {
        // Get the user's access token from the database or session
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $access_token = $user->access_token;
    
        $start_time = date('Y-m-d H:i:s');
        $dt = new DateTime($start_time);
        $iso_date_time = $dt->format(DateTime::ISO8601);
        $dist = 0;
        
        $challenges = Challenge::where('hasEnded', true)
        ->get();
        foreach ($challenges as $challenge) {
                // Use the Strava API to create a new activity
                $response = Http::withToken($access_token)->post('https://www.strava.com/api/v3/activities', [
                'type' => $challenge->activity_type,
                'start_date_local' => $challenge->$iso_date_time,
                'elapsed_time' => $challenge->$dist,
                'total_distance_km' => $challenge->distance*1000,
            ]);
        }
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
