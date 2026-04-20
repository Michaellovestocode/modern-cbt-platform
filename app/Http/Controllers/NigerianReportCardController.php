<?php

namespace App\Http\Controllers;

use App\Models\ReportCard;
use App\Models\Score;
use App\Models\Session;
use App\Models\Term;
use App\Models\User;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class NigerianReportCardController extends Controller
{
    // ========== VIEW REPORT CARDS ==========
    
    public function index()
    {

$activeSession = Session::getActive();
        $activeTerm = Term::getActive();
        
        $reportCards = ReportCard::with(['student', 'session', 'term', 'class'])
            ->latest()
            ->paginate(20);
        
        return view('admin.report-cards.index', compact('reportCards', 'activeSession', 'activeTerm'));
    }
    
    // ========== GENERATE REPORT CARD FOR STUDENT ==========
    
    public function generate($studentId)
    {
        $activeSession = Session::getActive();
        $activeTerm = Term::getActive();
        
        if (!$activeSession || !$activeTerm) {
            return redirect()->back()->with('error', 'No active session or term. Please contact admin.');
        }
        
        $student = User::with('class')->findOrFail($studentId);
        
        // Get all scores for this student
        $scores = Score::where('student_id', $studentId)
            ->where('session_id', $activeSession->id)
            ->where('term_id', $activeTerm->id)
            ->with('subject')
            ->orderBy('subject_id')
            ->get();
        
        if ($scores->isEmpty()) {
            return redirect()->back()->with('error', 'No scores found for this student.');
        }
        
        // Generate or update report card
        $summary = ReportCard::generateForStudent($studentId, $activeSession->id, $activeTerm->id);
        
        $reportCard = ReportCard::updateOrCreate(
            [
                'student_id' => $studentId,
                'session_id' => $activeSession->id,
                'term_id' => $activeTerm->id,
            ],
            array_merge($summary, [
                'class_id' => $student->class_id,
                'status' => 'generated',
                // Attendance - you can update these manually or from attendance system
                'days_school_opened' => 134,
                'days_present' => 128,
                'days_absent' => 6,
                'attendance_percentage' => 95.52,
            ])
        );
        
        return redirect()->route('admin.report-cards.preview', $reportCard->id)
            ->with('success', 'Report card generated successfully!');
    }
    
    // ========== PREVIEW REPORT CARD ==========
    
    public function preview($reportCardId)
    {
        $reportCard = ReportCard::with(['student.class', 'session', 'term'])
            ->findOrFail($reportCardId);
        
        // Get scores
        $scores = Score::where('student_id', $reportCard->student_id)
            ->where('session_id', $reportCard->session_id)
            ->where('term_id', $reportCard->term_id)
            ->with('subject')
            ->orderBy('subject_id')
            ->get();
        
        // Get school settings
        $schoolSettings = \App\Models\SchoolSettings::first() ?? new \App\Models\SchoolSettings();
        
        // Color schemes
        $colorSchemes = [
            'blue' => ['primary' => '#1E40AF', 'secondary' => '#3B82F6', 'light' => '#DBEAFE'],
            'green' => ['primary' => '#15803D', 'secondary' => '#22C55E', 'light' => '#DCFCE7'],
            'brown' => ['primary' => '#78350F', 'secondary' => '#A16207', 'light' => '#FEF3C7'],
            'pink' => ['primary' => '#BE123C', 'secondary' => '#F472B6', 'light' => '#FCE7F3'],
            'purple' => ['primary' => '#6B21A8', 'secondary' => '#A855F7', 'light' => '#F3E8FF'],
        ];
        
        $selectedColor = $colorSchemes[$reportCard->theme_color ?? 'blue'] ?? $colorSchemes['blue'];
        $colors = ['blue', 'green', 'brown', 'pink', 'purple'];
        
        return view('admin.report-cards.preview', compact('reportCard', 'scores', 'colors', 'schoolSettings', 'selectedColor'));
    }
    
    // ========== DOWNLOAD PDF ==========
    
    public function downloadPDF($reportCardId, Request $request)
    {
        $reportCard = ReportCard::with(['student.class', 'session', 'term'])
            ->findOrFail($reportCardId);
        
        // Get color theme
        $color = $request->get('color', $reportCard->theme_color ?? 'blue');
        
        // Update color if changed
        if ($reportCard->theme_color != $color) {
            $reportCard->update(['theme_color' => $color]);
        }
        
        // Get scores
        $scores = Score::where('student_id', $reportCard->student_id)
            ->where('session_id', $reportCard->session_id)
            ->where('term_id', $reportCard->term_id)
            ->with('subject')
            ->orderBy('subject_id')
            ->get();
        
        // Get school settings
        $schoolSettings = \App\Models\SchoolSettings::first() ?? new \App\Models\SchoolSettings();
        
        // Color schemes
        $colorSchemes = [
            'blue' => ['primary' => '#1E40AF', 'secondary' => '#3B82F6', 'light' => '#DBEAFE'],
            'green' => ['primary' => '#15803D', 'secondary' => '#22C55E', 'light' => '#DCFCE7'],
            'brown' => ['primary' => '#78350F', 'secondary' => '#A16207', 'light' => '#FEF3C7'],
            'pink' => ['primary' => '#BE123C', 'secondary' => '#F472B6', 'light' => '#FCE7F3'],
            'purple' => ['primary' => '#6B21A8', 'secondary' => '#A855F7', 'light' => '#F3E8FF'],
        ];
        
        $selectedColor = $colorSchemes[$color] ?? $colorSchemes['blue'];
        
        // Generate PDF
        $pdf = Pdf::loadView('admin.report-cards.nigerian-pdf', compact(
            'reportCard',
            'scores',
            'schoolSettings',
            'selectedColor'
        ));
        
        $pdf->setPaper('A4', 'portrait');
        
        $filename = "Report_Card_{$reportCard->student->name}_{$reportCard->session->name}_{$reportCard->term->name}.pdf";
        $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $filename);
        
        // Save PDF path
        $reportCard->update(['pdf_path' => $filename]);
        
        return $pdf->download($filename);
    }
    
    // ========== BULK GENERATE FOR CLASS ==========
    
    public function bulkGenerate(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
        ]);
        
        $activeSession = Session::getActive();
        $activeTerm = Term::getActive();
        
        $students = User::where('class_id', $request->class_id)
            ->where('role', 'student')
            ->get();
        
        $generated = 0;
        
        foreach ($students as $student) {
            $summary = ReportCard::generateForStudent($student->id, $activeSession->id, $activeTerm->id);
            
            if ($summary) {
                ReportCard::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'session_id' => $activeSession->id,
                        'term_id' => $activeTerm->id,
                    ],
                    array_merge($summary, [
                        'class_id' => $request->class_id,
                        'status' => 'generated',
                        'days_school_opened' => 134,
                        'days_present' => 128,
                        'days_absent' => 6,
                        'attendance_percentage' => 95.52,
                    ])
                );
                
                $generated++;
            }
        }
        
        return redirect()->back()->with('success', "Generated {$generated} report cards!");
    }
    
    // ========== UPDATE COMMENTS ==========
    
    public function updateComments(Request $request, $reportCardId)
    {
        $request->validate([
            'class_teacher_comment' => 'nullable|string|max:500',
            'head_teacher_comment' => 'nullable|string|max:500',
        ]);
        
        $reportCard = ReportCard::findOrFail($reportCardId);
        
        $reportCard->update([
            'class_teacher_comment' => $request->class_teacher_comment,
            'head_teacher_comment' => $request->head_teacher_comment,
        ]);
        
        return redirect()->back()->with('success', 'Comments updated successfully!');
    }
}
