<?php

namespace App\Http\Controllers;

use App\Models\HbsClass;
use App\Models\Participation;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function index()
    {
        $classes = HbsClass::where('user_id', auth()->id())
            ->with(['participations', 'grade'])
            ->get();
        
        return view('classes.index', compact('classes'));
    }

    public function checkParticipation(Request $request, HbsClass $class)
    {
        // Ensure the class belongs to the authenticated user
        if ($class->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $date = $request->input('date', today()->toDateString());
        
        $hasParticipation = Participation::where('user_id', auth()->id())
            ->where('class_id', $class->id)
            ->where('date', $date)
            ->exists();
        
        return response()->json(['has_participation' => $hasParticipation]);
    }

    public function toggleParticipation(Request $request, HbsClass $class)
    {
        // Ensure the class belongs to the authenticated user
        if ($class->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $date = $request->input('date', today()->toDateString());
        
        $participation = Participation::where('user_id', auth()->id())
            ->where('class_id', $class->id)
            ->where('date', $date)
            ->first();
        
        if ($participation) {
            $participation->delete();
            return response()->json(['status' => 'removed']);
        } else {
            Participation::create([
                'user_id' => auth()->id(),
                'class_id' => $class->id,
                'date' => $date,
            ]);
            return response()->json(['status' => 'added']);
        }
    }

    public function updateGrade(Request $request, HbsClass $class)
    {
        // Ensure the class belongs to the authenticated user
        if ($class->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'midterm' => 'nullable|numeric|min:0|max:100',
            'homework' => 'nullable|numeric|min:0|max:100',
            'final' => 'nullable|numeric|min:0|max:100',
        ]);

        $validated['user_id'] = auth()->id();

        $grade = Grade::updateOrCreate(
            ['user_id' => auth()->id(), 'class_id' => $class->id],
            $validated
        );

        return response()->json(['status' => 'success', 'grade' => $grade]);
    }
}
