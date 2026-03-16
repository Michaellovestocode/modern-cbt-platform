<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Artisan; 
use App\Http\Controllers\AdminController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Database Setup Route (TEMPORARY - Remove after first use)
|--------------------------------------------------------------------------
*/

Route::get('/setup-database', function() {
    try {
        $output = "<style>
            body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
            h1 { color: #10b981; }
            h2 { color: #3b82f6; margin-top: 30px; }
            pre { background: #f3f4f6; padding: 15px; border-radius: 8px; overflow-x: auto; }
            .success { color: #10b981; font-weight: bold; }
            .error { color: #ef4444; font-weight: bold; }
            a { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #3b82f6; color: white; text-decoration: none; border-radius: 8px; }
            a:hover { background: #2563eb; }
        </style>";
        
        $output .= "<h1>🚀 Database Setup</h1>";
        
        // Run migrations
        $output .= "<h2>📊 Running Migrations...</h2>";
        Artisan::call('migrate', ['--force' => true]);
        $migrations = Artisan::output();
        $output .= "<pre>{$migrations}</pre>";
        
        // Run seeders
        $output .= "<h2>🌱 Seeding Database...</h2>";
        if (User::count() == 0) {
            Artisan::call('db:seed', ['--force' => true]);
            $seeding = Artisan::output();
            $output .= "<pre>{$seeding}</pre>";
            $output .= "<p class='success'>✅ Database seeded successfully!</p>";
        } else {
            $output .= "<p class='success'>ℹ️ Database already has users. Skipping seeders.</p>";
            $output .= "<p>User count: " . User::count() . "</p>";
        }
        
        // Storage link
        $output .= "<h2>🔗 Creating Storage Link...</h2>";
        try {
            Artisan::call('storage:link');
            $storage = Artisan::output();
            $output .= "<pre>{$storage}</pre>";
        } catch (\Exception $e) {
            $output .= "<p class='success'>ℹ️ Storage link already exists.</p>";
        }
        
        // Clear cache
        $output .= "<h2>🧹 Clearing Cache...</h2>";
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        $output .= "<p class='success'>✅ Cache cleared!</p>";
        
        // Show demo credentials
        $output .= "<h2>🔑 Demo Credentials</h2>";
        $output .= "<pre>
Admin:   admin@school.com / password
Teacher: okafor@school.com / password  
Student: STD2024001 / password
        </pre>";
        
        $output .= "<p class='success'>✅ Setup Complete!</p>";
        $output .= "<a href='/'>Go to Login Page →</a>";
        
        return $output;
        
    } catch (\Exception $e) {
        return "<h1 class='error'>❌ Setup Failed</h1>
                <pre>" . $e->getMessage() . "</pre>
                <pre>" . $e->getTraceAsString() . "</pre>";
    }
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
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
        Route::get('/attempt/{attempt}/result', [StudentController::class, 'viewResult'])->name('view-result');
Route::get('/attempt/{attempt}/download-pdf', [StudentController::class, 'downloadResultPDF'])->name('download-result-pdf');
Route::get('/attempt/{attempt}/download-word', [StudentController::class, 'downloadResultWord'])->name('download-result-word');
    });

    // Admin/Teacher routes
    // Admin/Teacher routes
Route::prefix('admin')->name('admin.')->middleware('role:admin,teacher')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Teacher Management (Admin Only)
    // Student Management (Admin Only)
Route::middleware('role:admin')->group(function () {
    Route::get('/teachers', [AdminController::class, 'teachers'])->name('teachers');
    Route::get('/teachers/create', [AdminController::class, 'createTeacher'])->name('teacher.create');
    Route::post('/teachers', [AdminController::class, 'storeTeacher'])->name('teacher.store');
    Route::get('/teachers/{teacher}/edit', [AdminController::class, 'editTeacher'])->name('teacher.edit');
    Route::put('/teachers/{teacher}', [AdminController::class, 'updateTeacher'])->name('teacher.update');
    Route::delete('/teachers/{teacher}', [AdminController::class, 'deleteTeacher'])->name('teacher.delete');
    
    // Add these new student routes
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
    });
    
    // Exams (existing routes)
        
        // Exams
        Route::get('/exams', [AdminController::class, 'exams'])->name('exams');
        Route::get('/exams/create', [AdminController::class, 'createExam'])->name('exam.create');
        Route::post('/exams', [AdminController::class, 'storeExam'])->name('exam.store');
        Route::get('/exams/{exam}/edit', [AdminController::class, 'editExam'])->name('exam.edit');
Route::put('/exams/{exam}', [AdminController::class, 'updateExam'])->name('exam.update');
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
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/admin/exams/{attempt}/submit-for-student', [AdminExamController::class, 'submitForStudent'])
    ->name('admin.submit-for-student');

    Route::get('/admin/exams/{attempt}/preview', [AdminExamController::class, 'previewAttempt'])
    ->name('admin.attempt.preview');












    