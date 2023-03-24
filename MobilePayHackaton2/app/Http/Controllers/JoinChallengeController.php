<?php

namespace App\Http\Controllers;

use App\Models\ChallengeActivity;
use Illuminate\Http\Request;

class JoinChallengeController extends Controller
{
    public function index($eventID) {  
        $challengeActivity = ChallengeActivity::create([
            'stravaID' => session('stravaID'),
            'challengeID' => $eventID,
            'startingDistance' => 0,
        ]);

        $challengeActivity->save();

        return redirect()->route('home');
    }
}
