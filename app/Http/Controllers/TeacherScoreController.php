<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\Term;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Score;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherScoreController extends Controller
{
    // ========== SCORE ENTRY DASHBOARD ==========
    
    public function dashboard()
    {
        $teacher = Auth::user();
        $activeSession = Session::getActive();
        $activeTerm = Term::getActive();
        
        // Get subjects taught by this teacher
        $teacherSubjects = Subject::active()->get(); // You can filter by teacher assignment later
        
        // Get classes
        $classes = SchoolClass::all();
        
        // Get statistics
        $totalScoresEntered = Score::where('teacher_id', $teacher->id)
            ->where('session_id', $activeSession->id ?? null)
            ->where('term_id', $activeTerm->id ?? null)
            ->count();
        
        $pendingScores = Score::where('teacher_id', $teacher->id)
            ->where('status', 'draft')
            ->count();
        
        $submittedScores = Score::where('teacher_id', $teacher->id)
            ->where('status', 'submitted')
            ->count();
        
        return view('teacher.scores.dashboard', compact(
            'activeSession', 
            'activeTerm', 
            'teacherSubjects', 
            'classes',
            'totalScoresEntered',
            'pendingScores',
            'submittedScores'
        ));
    }
    
    // ========== SELECT CLASS & SUBJECT ==========
    
    public function selectClassSubject()
    {
        $activeSession = Session::getActive();
        $activeTerm = Term::getActive();
        
        if (!$activeSession || !$activeTerm) {
            return redirect()->back()->with('error', 'No active session or term found. Please contact admin.');
        }
        
        $classes = SchoolClass::all();
        $subjects = Subject::active()->ordered()->get();
        
        return view('teacher.scores.select', compact('classes', 'subjects', 'activeSession', 'activeTerm'));
    }
    
    // ========== ENTER SCORES ==========
    
    public function enterScores(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);
        
        $teacher = Auth::user();
        $activeSession = Session::getActive();
        $activeTerm = Term::getActive();
        
        $class = SchoolClass::findOrFail($request->class_id);
        $subject = Subject::findOrFail($request->subject_id);
        
        // Get all students in the class
        $students = User::where('class_id', $request->class_id)
            ->where('role', 'student')
            ->orderBy('name')
            ->get();
        
        // Get existing scores
        $scores = Score::where('class_id', $request->class_id)
            ->where('subject_id', $request->subject_id)
            ->where('session_id', $activeSession->id)
            ->where('term_id', $activeTerm->id)
            ->get()
            ->keyBy('student_id');
        
        return view('teacher.scores.enter', compact(
            'class', 
            'subject', 
            'students', 
            'scores', 
            'activeSession', 
            'activeTerm'
        ));
    }
    
    // ========== SAVE SCORES ==========
    
    public function saveScores(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'scores' => 'required|array',
            'scores.*.student_id' => 'required|exists:users,id',
            'scores.*.ca1' => 'nullable|numeric|min:0|max:10',
            'scores.*.ca2' => 'nullable|numeric|min:0|max:10',
            'scores.*.ca3' => 'nullable|numeric|min:0|max:10',
            'scores.*.exam' => 'nullable|numeric|min:0|max:70',
        ]);
        
        $teacher = Auth::user();
        $activeSession = Session::getActive();
        $activeTerm = Term::getActive();
        
        DB::beginTransaction();
        
        try {
            foreach ($request->scores as $scoreData) {
                // Skip if all fields are empty
                if (empty($scoreData['ca1']) && empty($scoreData['ca2']) && 
                    empty($scoreData['ca3']) && empty($scoreData['exam'])) {
                    continue;
                }
                
                Score::updateOrCreate(
                    [
                        'student_id' => $scoreData['student_id'],
                        'subject_id' => $request->subject_id,
                        'session_id' => $activeSession->id,
                        'term_id' => $activeTerm->id,
                    ],
                    [
                        'class_id' => $request->class_id,
                        'teacher_id' => $teacher->id,
                        'ca1' => $scoreData['ca1'] ?? 0,
                        'ca2' => $scoreData['ca2'] ?? 0,
                        'ca3' => $scoreData['ca3'] ?? 0,
                        'exam' => $scoreData['exam'] ?? 0,
                        'status' => 'draft',
                    ]
                );
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Scores saved successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error saving scores: ' . $e->getMessage());
        }
    }
    
    // ========== SUBMIT SCORES FOR APPROVAL ==========
    
    public function submitScores(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);
        
        $teacher = Auth::user();
        $activeSession = Session::getActive();
        $activeTerm = Term::getActive();
        
        $count = Score::where('teacher_id', $teacher->id)
            ->where('class_id', $request->class_id)
            ->where('subject_id', $request->subject_id)
            ->where('session_id', $activeSession->id)
            ->where('term_id', $activeTerm->id)
            ->where('status', 'draft')
            ->update(['status' => 'submitted']);
        
        return redirect()->route('teacher.scores.dashboard')
            ->with('success', "{$count} scores submitted for approval!");
    }
    
    // ========== VIEW MY SUBMITTED SCORES ==========
    
    public function myScores()
    {
        $teacher = Auth::user();
        $activeSession = Session::getActive();
        $activeTerm = Term::getActive();
        
        $scores = Score::where('teacher_id', $teacher->id)
            ->where('session_id', $activeSession->id ?? null)
            ->where('term_id', $activeTerm->id ?? null)
            ->with(['student', 'subject', 'class'])
            ->latest()
            ->paginate(50);
        
        return view('teacher.scores.my-scores', compact('scores', 'activeSession', 'activeTerm'));
    }
}
