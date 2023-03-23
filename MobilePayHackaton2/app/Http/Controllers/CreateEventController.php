<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Challenge;
use Inertia\Inertia;

class CreateEventController extends Controller
{
    public function index() {       
        $challenges = Challenge::all();
        return Inertia::render('Home', compact("challenges"));        
    }

    public function show($eventID) {       
        $challenge = Challenge::where('id', $eventID)->get();
        return Inertia::render('Event', compact("challenge"));        
    }

    public function store(Request $request) {       
        $data = $this->validateCreate($request);

        $challenge = Challenge::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'max_score' => $data['max_score'],
            'type' => $data['type'],
            'total_distance_km' => $data['total_distance_km'],
        ]);

        $challenge->save();

        $challenges = Challenge::all();
        return Inertia::render('Home', compact("challenges"));        
    }

    private function validateCreate(Request $request) {
        return $request->validate([
            'name' => ['required', 'string', 'max:50', 'min:2'],
            'description' => ['nullable', 'string', 'max:50'],
            'max_score' => ['required', 'integer', "min:1"],
            'type' => ['required', 'string'],
            'total_distance_km' => ['required', 'numeric', "min: 0.1"],
        ]);          
    }
}