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
        $classes = HbsClass::with(['participations', 'grade'])->get();
        
        return view('classes.index', compact('classes'));
    }

    public function toggleParticipation(Request $request, HbsClass $class)
    {
        $date = $request->input('date', today()->toDateString());
        
        $participation = Participation::where('class_id', $class->id)
            ->where('date', $date)
            ->first();
        
        if ($participation) {
            $participation->delete();
            return response()->json(['status' => 'removed']);
        } else {
            Participation::create([
                'class_id' => $class->id,
                'date' => $date,
            ]);
            return response()->json(['status' => 'added']);
        }
    }

    public function updateGrade(Request $request, HbsClass $class)
    {
        $validated = $request->validate([
            'midterm' => 'nullable|numeric|min:0|max:100',
            'homework' => 'nullable|numeric|min:0|max:100',
            'final' => 'nullable|numeric|min:0|max:100',
        ]);

        $grade = Grade::updateOrCreate(
            ['class_id' => $class->id],
            $validated
        );

        return response()->json(['status' => 'success', 'grade' => $grade]);
    }
}
