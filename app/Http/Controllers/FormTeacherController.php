<?php

namespace App\Http\Controllers;

use App\Models\FormTeacher;
use App\Models\SchoolClass;
use App\Models\User;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormTeacherController extends Controller
{
    /**
     * List all form teacher assignments
     */
    public function index()
    {
        $formTeachers = FormTeacher::with(['teacher', 'schoolClass'])
            ->orderBy('is_active', 'desc')
            ->orderBy('assigned_date', 'desc')
            ->paginate(15);

        return view('admin.form-teachers.index', compact('formTeachers'));
    }

    /**
     * Show form to assign a form teacher to a class
     */
    public function create()
    {
        $classes = SchoolClass::all();
        $teachers = User::where('role', 'teacher')->get();
        
        // Get classes that don't have a form teacher yet
        $unassignedClasses = $classes->filter(function ($class) {
            return !$class->formTeacher()->exists();
        });

        return view('admin.form-teachers.create', compact('unassignedClasses', 'teachers'));
    }

    /**
     * Store form teacher assignment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:school_classes,id|unique:form_teachers,class_id',
        ]);

        // Verify teacher exists and has teacher role
        $teacher = User::findOrFail($validated['teacher_id']);
        if ($teacher->role !== 'teacher') {
            return back()->withErrors(['teacher_id' => 'Selected user must be a teacher.']);
        }

        FormTeacher::create([
            'teacher_id' => $validated['teacher_id'],
            'class_id' => $validated['class_id'],
            'assigned_date' => now(),
            'is_active' => true,
        ]);

        return redirect()->route('admin.form-teachers.index')
            ->with('success', 'Form teacher assigned successfully!');
    }

    /**
     * Show form teacher details
     */
    public function show($id)
    {
        $formTeacher = FormTeacher::with(['teacher', 'schoolClass'])->findOrFail($id);
        
        // Get class students
        $students = $formTeacher->schoolClass->users()->where('role', 'student')->get();
        
        // Get class exams
        $exams = $formTeacher->schoolClass->exams()->get();
        
        // Get exam attempts for class students
        $attemptsByExam = [];
        foreach ($exams as $exam) {
            $attempts = ExamAttempt::where('exam_id', $exam->id)
                ->whereIn('student_id', $students->pluck('id'))
                ->with('student', 'exam')
                ->get();
            $attemptsByExam[$exam->id] = $attempts;
        }

        return view('admin.form-teachers.show', compact('formTeacher', 'students', 'exams', 'attemptsByExam'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $formTeacher = FormTeacher::with(['teacher', 'schoolClass'])->findOrFail($id);
        $teachers = User::where('role', 'teacher')->get();
        $classes = SchoolClass::all();

        return view('admin.form-teachers.edit', compact('formTeacher', 'teachers', 'classes'));
    }

    /**
     * Update form teacher assignment
     */
    public function update(Request $request, $id)
    {
        $formTeacher = FormTeacher::findOrFail($id);

        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'is_active' => 'boolean',
        ]);

        // Verify teacher exists and has teacher role
        $teacher = User::findOrFail($validated['teacher_id']);
        if ($teacher->role !== 'teacher') {
            return back()->withErrors(['teacher_id' => 'Selected user must be a teacher.']);
        }

        $formTeacher->update([
            'teacher_id' => $validated['teacher_id'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.form-teachers.index')
            ->with('success', 'Form teacher updated successfully!');
    }

    /**
     * Delete form teacher assignment
     */
    public function destroy($id)
    {
        $formTeacher = FormTeacher::findOrFail($id);
        $className = $formTeacher->schoolClass->name;
        
        $formTeacher->delete();

        return redirect()->route('admin.form-teachers.index')
            ->with('success', "Form teacher removed from {$className}.");
    }

    /**
     * Form teacher dashboard (for teachers)
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get all classes where user is form teacher
        $formTeacherAssignments = FormTeacher::where('teacher_id', $user->id)
            ->where('is_active', true)
            ->with('schoolClass')
            ->get();

        $data = [];
        
        foreach ($formTeacherAssignments as $assignment) {
            $class = $assignment->schoolClass;
            $students = $class->users()->where('role', 'student')->get();
            
            // Get class exams
            $exams = $class->exams()->get();
            
            // Get exam results summary
            $totalExams = $exams->count();
            $totalStudents = $students->count();
            
            $attempts = ExamAttempt::whereIn('student_id', $students->pluck('id'))
                ->whereIn('exam_id', $exams->pluck('id'))
                ->get();

            $averageScore = $attempts->count() > 0 
                ? $attempts->avg('score') 
                : 0;

            $data[] = [
                'assignment' => $assignment,
                'class' => $class,
                'studentCount' => $totalStudents,
                'examCount' => $totalExams,
                'attemptCount' => $attempts->count(),
                'averageScore' => number_format($averageScore, 2),
            ];
        }

        // Convert array to collection so isEmpty() works in the view
        $data = collect($data);

        return view('teacher.form-teacher-dashboard', compact('data'));
    }

    /**
     * View class results as form teacher
     */
    public function classResults($classId)
    {
        $user = Auth::user();
        
        // Verify user is form teacher for this class
        $formTeacher = FormTeacher::where('teacher_id', $user->id)
            ->where('class_id', $classId)
            ->firstOrFail();

        $class = $formTeacher->schoolClass;
        $students = $class->users()->where('role', 'student')->orderBy('name')->get();
        $exams = $class->exams()->orderBy('created_at', 'desc')->get();

        // Build comprehensive result matrix
        $resultMatrix = [];
        foreach ($students as $student) {
            $resultMatrix[$student->id] = [
                'name' => $student->name,
                'rollNumber' => $student->roll_number ?? 'N/A',
            ];
            
            foreach ($exams as $exam) {
                $attempt = ExamAttempt::where('student_id', $student->id)
                    ->where('exam_id', $exam->id)
                    ->first();
                
                $resultMatrix[$student->id][$exam->id] = $attempt 
                    ? [
                        'score' => $attempt->score,
                        'total_marks' => $attempt->total_marks,
                        'percentage' => $attempt->total_marks > 0 ? round(($attempt->score / $attempt->total_marks) * 100, 2) : 0,
                        'status' => $attempt->score >= $exam->pass_mark ? 'Pass' : 'Fail',
                    ]
                    : null;
            }
        }

        return view('teacher.class-results', compact('formTeacher', 'class', 'students', 'exams', 'resultMatrix'));
    }

    /**
     * Export class results as PDF
     */
    public function exportResults($classId)
    {
        $user = Auth::user();
        
        $formTeacher = FormTeacher::where('teacher_id', $user->id)
            ->where('class_id', $classId)
            ->firstOrFail();

        $class = $formTeacher->schoolClass;
        $students = $class->users()->where('role', 'student')->get();
        $exams = $class->exams()->get();

        // Build result summary
        $resultSummary = [];
        foreach ($students as $student) {
            $attempts = ExamAttempt::where('student_id', $student->id)
                ->whereIn('exam_id', $exams->pluck('id'))
                ->get();

            $totalScore = $attempts->sum('score');
            $totalMarks = $attempts->sum('total_marks');
            $average = $totalMarks > 0 ? ($totalScore / $totalMarks) * 100 : 0;

            $resultSummary[] = [
                'name' => $student->name,
                'rollNumber' => $student->roll_number ?? 'N/A',
                'totalScore' => $totalScore,
                'totalMarks' => $totalMarks,
                'average' => number_format($average, 2),
                'attempts' => $attempts->count(),
            ];
        }

        // Generate PDF
        $pdf = \PDF::loadView('teacher.class-results-pdf', [
            'class' => $class,
            'formTeacher' => $formTeacher,
            'resultSummary' => $resultSummary,
            'generatedDate' => now()->format('d M Y'),
        ]);

        return $pdf->download("class_{$class->id}_results_" . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Show form to add students to a class
     */
    public function showAddStudents($classId)
    {
        $user = Auth::user();
        
        // Verify user is form teacher for this class
        $formTeacher = FormTeacher::where('teacher_id', $user->id)
            ->where('class_id', $classId)
            ->firstOrFail();

        $class = $formTeacher->schoolClass;
        
        // Get students already in this class
        $studentsInClass = $class->users()->where('role', 'student')->pluck('id')->toArray();
        
        // Get all students not in this class
        $availableStudents = User::where('role', 'student')
            ->whereNotIn('id', $studentsInClass)
            ->orderBy('name')
            ->get();

        return view('teacher.add-students', compact('formTeacher', 'class', 'studentsInClass', 'availableStudents'));
    }

    /**
     * Add student to form teacher's class
     */
    public function storeStudentInClass(Request $request, $classId)
    {
        $user = Auth::user();
        
        // Verify user is form teacher for this class
        $formTeacher = FormTeacher::where('teacher_id', $user->id)
            ->where('class_id', $classId)
            ->firstOrFail();

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $student = User::findOrFail($validated['student_id']);
        
        // Verify student role
        if ($student->role !== 'student') {
            return back()->withErrors(['student_id' => 'Selected user must be a student.']);
        }

        // Check if student is already in class
        if ($student->class_id == $classId) {
            return back()->withErrors(['student_id' => 'Student is already in this class.']);
        }

        // Add student to class
        $student->update(['class_id' => $classId]);

        return back()->with('success', "{$student->name} has been added to {$formTeacher->schoolClass->name}.");
    }

    /**
     * Remove student from form teacher's class
     */
    public function removeStudentFromClass($classId, $studentId)
    {
        $user = Auth::user();
        
        // Verify user is form teacher for this class
        $formTeacher = FormTeacher::where('teacher_id', $user->id)
            ->where('class_id', $classId)
            ->firstOrFail();

        $student = User::where('id', $studentId)
            ->where('class_id', $classId)
            ->where('role', 'student')
            ->firstOrFail();

        $studentName = $student->name;
        
        // Remove student from class
        $student->update(['class_id' => null]);

        return back()->with('success', "{$studentName} has been removed from {$formTeacher->schoolClass->name}.");
    }

    /**
     * Show compile results page
     */
    public function compileResults($classId)
    {
        $user = Auth::user();
        
        // Verify user is form teacher for this class
        $formTeacher = FormTeacher::where('teacher_id', $user->id)
            ->where('class_id', $classId)
            ->firstOrFail();

        $class = $formTeacher->schoolClass;
        $students = $class->users()->where('role', 'student')->orderBy('name')->get();
        $exams = $class->exams()->orderBy('created_at', 'desc')->get();

        // Build comprehensive result matrix
        $resultMatrix = [];
        foreach ($students as $student) {
            $resultMatrix[$student->id] = [
                'name' => $student->name,
                'rollNumber' => $student->roll_number ?? 'N/A',
                'exams' => []
            ];
            
            foreach ($exams as $exam) {
                $attempt = ExamAttempt::where('student_id', $student->id)
                    ->where('exam_id', $exam->id)
                    ->first();
                
                $resultMatrix[$student->id]['exams'][$exam->id] = [
                    'exam_id' => $exam->id,
                    'exam_title' => $exam->title,
                    'score' => $attempt ? $attempt->score : null,
                    'total_marks' => $exam->total_marks,
                    'has_attempt' => $attempt ? true : false,
                    'attempt_id' => $attempt ? $attempt->id : null,
                ];
            }
        }

        return view('teacher.compile-results', compact('formTeacher', 'class', 'students', 'exams', 'resultMatrix'));
    }

    /**
     * Show form to manually enter/compile results
     */
    public function showCompileForm($classId)
    {
        $user = Auth::user();
        
        // Verify user is form teacher for this class
        $formTeacher = FormTeacher::where('teacher_id', $user->id)
            ->where('class_id', $classId)
            ->firstOrFail();

        $class = $formTeacher->schoolClass;
        $students = $class->users()->where('role', 'student')->orderBy('name')->get();
        $exams = $class->exams()->orderBy('created_at', 'desc')->get();

        return view('teacher.compile-form', compact('formTeacher', 'class', 'students', 'exams'));
    }

    /**
     * Store compiled/entered results
     */
    public function storeCompiledResults(Request $request, $classId)
    {
        $user = Auth::user();
        
        // Verify user is form teacher for this class
        $formTeacher = FormTeacher::where('teacher_id', $user->id)
            ->where('class_id', $classId)
            ->firstOrFail();

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'exam_id' => 'required|exists:exams,id',
            'score' => 'required|numeric|min:0',
        ]);

        $student = User::findOrFail($validated['student_id']);
        $exam = \App\Models\Exam::findOrFail($validated['exam_id']);

        // Verify student is in the class
        if ($student->class_id != $classId) {
            return back()->withErrors(['student_id' => 'Student is not in this class.']);
        }

        // Check if score is not greater than total marks
        if ($validated['score'] > $exam->total_marks) {
            return back()->withErrors(['score' => "Score cannot exceed total marks ({$exam->total_marks})."]);
        }

        // Check or create exam attempt
        $attempt = ExamAttempt::where('student_id', $validated['student_id'])
            ->where('exam_id', $validated['exam_id'])
            ->first();

        if ($attempt) {
            // Update existing attempt
            $attempt->update([
                'score' => $validated['score'],
                'is_submitted' => true,
                'submitted_at' => now(),
            ]);
            $message = "Result updated for {$student->name}";
        } else {
            // Create new attempt with the compiled result
            ExamAttempt::create([
                'student_id' => $validated['student_id'],
                'exam_id' => $validated['exam_id'],
                'score' => $validated['score'],
                'total_marks' => $exam->total_marks,
                'is_submitted' => true,
                'submitted_at' => now(),
            ]);
            $message = "Result compiled for {$student->name}";
        }

        return back()->with('success', "{$message} in {$exam->title}.");
    }
}
