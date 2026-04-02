@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-2">Manually Enter Result - {{ $class->name }}</h2>
            <p class="text-muted">Fill in exam results for your students</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Enter Student Result</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('teacher.form-teacher.store-compiled-results', $class->id) }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="student_id" class="form-label">Student <span class="text-danger">*</span></label>
                        <select class="form-select @error('student_id') is-invalid @enderror" 
                            id="student_id" name="student_id" required>
                            <option value="">-- Select Student --</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->registration_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="exam_id" class="form-label">Exam <span class="text-danger">*</span></label>
                        <select class="form-select @error('exam_id') is-invalid @enderror" 
                            id="exam_id" name="exam_id" required onchange="updateTotalMarks()">
                            <option value="">-- Select Exam --</option>
                            @foreach ($exams as $exam)
                                <option value="{{ $exam->id }}" 
                                    data-total-marks="{{ $exam->total_marks }}"
                                    {{ old('exam_id') == $exam->id ? 'selected' : '' }}>
                                    {{ $exam->title }} (Total: {{ $exam->total_marks }} marks)
                                </option>
                            @endforeach
                        </select>
                        @error('exam_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="score" class="form-label">Score Obtained <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('score') is-invalid @enderror" 
                            id="score" name="score" min="0" step="0.1" required
                            value="{{ old('score') }}" placeholder="Enter score">
                        @error('score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Maximum: <span id="maxMarks">-</span> marks</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Percentage</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="percentage" readonly placeholder="0%">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('teacher.form-teacher.compile-results', $class->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Result
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Recent Results Table -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Recently Entered Results</h5>
        </div>
        <div class="card-body">
            @php
                $recentAttempts = \App\Models\ExamAttempt::whereIn('student_id', $students->pluck('id'))
                    ->whereIn('exam_id', $exams->pluck('id'))
                    ->orderBy('updated_at', 'desc')
                    ->limit(10)
                    ->get();
            @endphp

            @if ($recentAttempts->isEmpty())
                <p class="text-muted">No results entered yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Exam</th>
                                <th>Score</th>
                                <th>Percentage</th>
                                <th>Date Entered</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentAttempts as $attempt)
                                <tr>
                                    <td>{{ $attempt->student->name }}</td>
                                    <td>{{ $attempt->exam->title }}</td>
                                    <td>{{ $attempt->score }}/{{ $attempt->total_marks }}</td>
                                    <td>
                                        @php
                                            $percentage = $attempt->total_marks > 0 ? round(($attempt->score / $attempt->total_marks) * 100, 1) : 0;
                                        @endphp
                                        {{ $percentage }}%
                                    </td>
                                    <td>{{ $attempt->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function updateTotalMarks() {
    const examSelect = document.getElementById('exam_id');
    const selectedOption = examSelect.options[examSelect.selectedIndex];
    const totalMarks = selectedOption.getAttribute('data-total-marks') || '-';
    document.getElementById('maxMarks').textContent = totalMarks;
    
    // Reset score and percentage
    document.getElementById('score').value = '';
    document.getElementById('percentage').value = '';
}

// Calculate percentage on score input
document.getElementById('score').addEventListener('input', function() {
    const examSelect = document.getElementById('exam_id');
    const selectedOption = examSelect.options[examSelect.selectedIndex];
    const totalMarks = parseInt(selectedOption.getAttribute('data-total-marks')) || 0;
    const score = parseFloat(this.value) || 0;
    
    if (totalMarks > 0) {
        const percentage = ((score / totalMarks) * 100).toFixed(2);
        document.getElementById('percentage').value = percentage;
    } else {
        document.getElementById('percentage').value = '';
    }
});

// Initialize on page load
window.addEventListener('load', updateTotalMarks);
</script>
@endsection
