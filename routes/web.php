<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherScoreController;
use App\Http\Controllers\NigerianReportCardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    // Redirect authenticated users to their dashboard
    if (auth()->check()) {
        $user = auth()->user();
        
        if ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        } elseif ($user->isTeacher() || $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
    }
    
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Student routes
    Route::prefix('student')->name('student.')->middleware('role:student')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/exam/{exam}/start', [StudentController::class, 'startExam'])->name('start-exam');
        Route::get('/attempt/{attempt}', [StudentController::class, 'takeExam'])->name('take-exam');
        Route::post('/attempt/{attempt}/save', [StudentController::class, 'saveAnswer'])->name('save-answer');
        Route::post('/attempt/{attempt}/submit', [StudentController::class, 'submitExam'])->name('submit-exam');
        Route::get('/attempt/{attempt}/result', [StudentController::class, 'viewResult'])->name('view-result');
        Route::get('/attempt/{attempt}/download-pdf', [StudentController::class, 'downloadResultPDF'])->name('download-result-pdf');
        Route::get('/attempt/{attempt}/download-word', [StudentController::class, 'downloadResultWord'])->name('download-result-word');
    });


    // Teacher Score Entry
Route::get('/teacher/scores', [TeacherScoreController::class, 'dashboard'])->name('teacher.scores.dashboard');
Route::get('/teacher/scores/select', [TeacherScoreController::class, 'selectClassSubject'])->name('teacher.scores.select');
Route::post('/teacher/scores/enter', [TeacherScoreController::class, 'enterScores'])->name('teacher.scores.enter');
Route::post('/teacher/scores/save', [TeacherScoreController::class, 'saveScores'])->name('teacher.scores.save');
Route::post('/teacher/scores/submit', [TeacherScoreController::class, 'submitScores'])->name('teacher.scores.submit');

// Nigerian Report Cards
Route::get('/admin/report-cards', [NigerianReportCardController::class, 'index'])->name('admin.report-cards');
Route::get('/admin/report-cards/generate/{student}', [NigerianReportCardController::class, 'generate'])->name('admin.report-cards.generate');
Route::get('/admin/report-cards/{id}/preview', [NigerianReportCardController::class, 'preview'])->name('admin.report-cards.preview');
Route::get('/admin/report-cards/{id}/download', [NigerianReportCardController::class, 'downloadPDF'])->name('admin.report-cards.download');
Route::post('/admin/report-cards/bulk', [NigerianReportCardController::class, 'bulkGenerate'])->name('admin.report-cards.bulk');
Route::post('/admin/report-cards/{id}/comments', [NigerianReportCardController::class, 'updateComments'])->name('admin.report-cards.comments');



    // Admin/Teacher routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin,teacher')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Admin-only routes
        Route::middleware('role:admin')->group(function () {
            // Teacher Management
            Route::get('/teachers', [AdminController::class, 'teachers'])->name('teachers');
            Route::get('/teachers/create', [AdminController::class, 'createTeacher'])->name('teacher.create');
            Route::post('/teachers', [AdminController::class, 'storeTeacher'])->name('teacher.store');
            Route::get('/teachers/{teacher}/edit', [AdminController::class, 'editTeacher'])->name('teacher.edit');
            Route::put('/teachers/{teacher}', [AdminController::class, 'updateTeacher'])->name('teacher.update');
            Route::delete('/teachers/{teacher}', [AdminController::class, 'deleteTeacher'])->name('teacher.delete');
            
            // Student Management
            Route::get('/students', [AdminController::class, 'students'])->name('students');
            Route::get('/students/create', [AdminController::class, 'createStudent'])->name('student.create');
            Route::post('/students', [AdminController::class, 'storeStudent'])->name('student.store');
            Route::get('/students/{student}/edit', [AdminController::class, 'editStudent'])->name('student.edit');
            Route::put('/students/{student}', [AdminController::class, 'updateStudent'])->name('student.update');
            Route::delete('/students/{student}', [AdminController::class, 'deleteStudent'])->name('student.delete');
            
            // Class Management
            Route::get('/classes', [AdminController::class, 'classes'])->name('classes');
            Route::post('/classes', [AdminController::class, 'storeClass'])->name('class.store');
            Route::delete('/classes/{class}', [AdminController::class, 'deleteClass'])->name('class.delete');

            // Subjects Management
            Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
            Route::get('/subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
            Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
            Route::get('/subjects/{subject}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
            Route::put('/subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
            Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');
            Route::get('/subjects/{subject}/teachers', [SubjectController::class, 'assignTeachers'])->name('subjects.assign-teachers');
            Route::put('/subjects/{subject}/teachers', [SubjectController::class, 'updateTeachers'])->name('subjects.update-teachers');
            Route::get('/teachers/{teacher}/subjects', [SubjectController::class, 'assignSubjects'])->name('subjects.assign-subjects');
            Route::put('/teachers/{teacher}/subjects', [SubjectController::class, 'updateSubjects'])->name('subjects.update-subjects');

            // Form Teacher Management - FormTeacherController was renamed to TeacherScoreController
            // These routes are commented out because TeacherScoreController has different methods
            // TODO: Implement these methods in TeacherScoreController or create a new FormTeacherController
            // Route::get('/form-teachers', [FormTeacherController::class, 'index'])->name('form-teachers.index');
            // Route::get('/form-teachers/create', [FormTeacherController::class, 'create'])->name('form-teachers.create');
            // Route::post('/form-teachers', [FormTeacherController::class, 'store'])->name('form-teachers.store');
            // Route::get('/form-teachers/{formTeacher}', [FormTeacherController::class, 'show'])->name('form-teachers.show');
            // Route::get('/form-teachers/{formTeacher}/edit', [FormTeacherController::class, 'edit'])->name('form-teachers.edit');
            // Route::put('/form-teachers/{formTeacher}', [FormTeacherController::class, 'update'])->name('form-teachers.update');
            // Route::delete('/form-teachers/{formTeacher}', [FormTeacherController::class, 'destroy'])->name('form-teachers.destroy');
        });
        
        // Exams (accessible by admin and teachers)
        Route::get('/exams', [AdminController::class, 'exams'])->name('exams');
        Route::get('/exams/create', [AdminController::class, 'createExam'])->name('exam.create');
        Route::post('/exams', [AdminController::class, 'storeExam'])->name('exam.store');
        Route::get('/exams/{exam}/edit', [AdminController::class, 'editExam'])->name('exam.edit');
        Route::put('/exams/{exam}', [AdminController::class, 'updateExam'])->name('exam.update');
        Route::delete('/exams/{exam}', [AdminController::class, 'deleteExam'])->name('exam.delete');
        Route::get('/exams/{exam}/questions', [AdminController::class, 'examQuestions'])->name('exam.questions');
        Route::post('/exams/{exam}/questions', [AdminController::class, 'storeQuestion'])->name('exam.question.store');
        Route::delete('/questions/{question}', [AdminController::class, 'deleteQuestion'])->name('question.delete');
        
        // Results & Grading
        Route::get('/exams/{exam}/results', [AdminController::class, 'examResults'])->name('exam.results');
        Route::get('/attempts/{attempt}/grade', [AdminController::class, 'gradeAttempt'])->name('attempt.grade');
        Route::post('/attempts/{attempt}/grade', [AdminController::class, 'updateGrading'])->name('attempt.update-grade');
        
        // Exports
        Route::get('/exams/{exam}/export/pdf', [AdminController::class, 'exportResultsPDF'])->name('exam.export.pdf');
        Route::get('/exams/{exam}/export/word', [AdminController::class, 'exportResultsWord'])->name('exam.export.word');
        Route::get('/attempts/{attempt}/print', [AdminController::class, 'printScript'])->name('attempt.print');
        
        // Results Portal Routes
        Route::prefix('results')->name('results.')->withoutMiddleware('verified')->group(function () {
            Route::get('/', [ResultsController::class, 'index'])->name('index');
            Route::get('/statistics', [ResultsController::class, 'statistics'])->name('statistics');
            Route::get('/exam/{exam}', [ResultsController::class, 'examWise'])->name('exam-wise');
            Route::get('/class/{class}', [ResultsController::class, 'classWise'])->name('class-wise');
            Route::get('/student/{student}', [ResultsController::class, 'studentResults'])->name('student');
            Route::get('/export/pdf', [ResultsController::class, 'exportPDF'])->name('export-pdf');
            Route::get('/export/csv', [ResultsController::class, 'exportCSV'])->name('export-csv');
        });
    });

    // Form Teacher routes (for teachers) - FormTeacherController was renamed to TeacherScoreController
    // These routes are commented out because the methods don't exist in TeacherScoreController
    // TODO: Implement these methods or create a new FormTeacherController
    // Route::prefix('teacher')->name('teacher.')->middleware('role:teacher')->group(function () {
    //     Route::get('/form-teacher/dashboard', [FormTeacherController::class, 'dashboard'])->name('form-teacher.dashboard');
    //     Route::get('/form-teacher/class/{class}/results', [FormTeacherController::class, 'classResults'])->name('form-teacher.class-results');
    //     Route::get('/form-teacher/class/{class}/export', [FormTeacherController::class, 'exportResults'])->name('form-teacher.export-results');
    //     
    //     // Form Teacher - Add Students
    //     Route::get('/form-teacher/class/{class}/students/add', [FormTeacherController::class, 'showAddStudents'])->name('form-teacher.add-students');
    //     Route::post('/form-teacher/class/{class}/students', [FormTeacherController::class, 'storeStudentInClass'])->name('form-teacher.store-student');
    //     Route::delete('/form-teacher/class/{class}/students/{student}', [FormTeacherController::class, 'removeStudentFromClass'])->name('form-teacher.remove-student');
    //     
    //     // Form Teacher - Compile Results
    //     Route::get('/form-teacher/class/{class}/compile-results', [FormTeacherController::class, 'compileResults'])->name('form-teacher.compile-results');
    //     Route::get('/form-teacher/class/{class}/compile-results/form', [FormTeacherController::class, 'showCompileForm'])->name('form-teacher.compile-form');
    //     Route::post('/form-teacher/class/{class}/compile-results', [FormTeacherController::class, 'storeCompiledResults'])->name('form-teacher.store-compiled-results');
    //     
    //     // Form Teacher - Report Cards
    //     Route::get('/report-cards', [FormTeacherController::class, 'reportCards'])->name('report-cards');
    //     Route::get('/report-cards/create', [FormTeacherController::class, 'showReportCardForm'])->name('report-card.create');
    //     Route::get('/report-cards/student/{student}', [FormTeacherController::class, 'showReportCardForm'])->name('report-card.edit');
    //     Route::post('/report-cards', [FormTeacherController::class, 'storeReportCard'])->name('report-card.store');
    //     Route::get('/report-cards/{reportCard}/pdf', [FormTeacherController::class, 'generateReportCardPDF'])->name('report-card.pdf');
    //     Route::delete('/report-cards/{reportCard}', [FormTeacherController::class, 'deleteReportCard'])->name('report-card.delete');
    // });
});

// Temporary route to fix exam total marks
Route::get('/fix-exam-totals', function() {
    $exams = \App\Models\Exam::all();
    $fixed = 0;
    $report = '<h1>Exam Totals Fix Report</h1><table border="1" style="border-collapse:collapse; padding:10px;"><tr><th>Exam</th><th>Old Total</th><th>New Total</th><th>Status</th></tr>';
    
    foreach ($exams as $exam) {
        $oldTotal = $exam->total_marks;
        $correctTotal = $exam->questions()->sum('marks');
        
        if ($oldTotal != $correctTotal) {
            $exam->update(['total_marks' => $correctTotal]);
            $report .= "<tr><td>{$exam->title}</td><td style='color:red'>{$oldTotal}</td><td style='color:green'>{$correctTotal}</td><td>✅ Fixed</td></tr>";
            $fixed++;
        } else {
            $report .= "<tr><td>{$exam->title}</td><td>{$oldTotal}</td><td>{$correctTotal}</td><td>✓ Already correct</td></tr>";
        }
    }
    
    $report .= '</table><br><h2 style="color:green">✅ Fixed ' . $fixed . ' exam(s)!</h2>';
    $report .= '<br><a href="/">Go to Homepage</a>';
    
    return $report;
});