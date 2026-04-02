@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-2">Compile Results - {{ $class->name }}</h2>
            <p class="text-muted">View and manage exam results for {{ $class->name }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('teacher.form-teacher.compile-form', $class->id) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Manually Enter Result
            </a>
            <a href="{{ route('teacher.form-teacher.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    @if ($students->isEmpty())
        <div class="alert alert-warning">
            No students have been added to this class yet.
            <a href="{{ route('teacher.form-teacher.add-students', $class->id) }}">Add students now</a>
        </div>
    @elseif ($exams->isEmpty())
        <div class="alert alert-info">
            No exams have been created for this class yet.
        </div>
    @else
        <!-- Results Matrix -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Class Results Matrix</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Student Name</th>
                                <th>Roll Number</th>
                                @foreach ($exams as $exam)
                                    <th class="text-center">
                                        {{ substr($exam->title, 0, 15) }}
                                        <br>
                                        <small class="text-muted">(Out of {{ $exam->total_marks }})</small>
                                    </th>
                                @endforeach
                                <th>Average Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($resultMatrix as $studentId => $result)
                                <tr>
                                    <td><strong>{{ $result['name'] }}</strong></td>
                                    <td>{{ $result['rollNumber'] }}</td>
                                    
                                    @php
                                        $totalScore = 0;
                                        $examCount = 0;
                                    @endphp
                                    
                                    @foreach ($result['exams'] as $examId => $examResult)
                                        <td class="text-center">
                                            @if ($examResult['has_attempt'])
                                                <span class="badge bg-success">
                                                    {{ $examResult['score'] }}/{{ $examResult['total_marks'] }}
                                                </span>
                                                <br>
                                                <small class="text-muted">
                                                    {{ round(($examResult['score'] / $examResult['total_marks']) * 100, 1) }}%
                                                </small>
                                                @php
                                                    $totalScore += $examResult['score'];
                                                    $examCount++;
                                                @endphp
                                            @else
                                                <span class="badge bg-secondary">Pending</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    
                                    <td class="text-center">
                                        @if ($examCount > 0)
                                            <strong>{{ round($totalScore / $examCount, 1) }}%</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-primary">{{ $students->count() }}</h3>
                        <p class="mb-0">Total Students</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-info">{{ $exams->count() }}</h3>
                        <p class="mb-0">Total Exams</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-warning">
                            @php
                                $totalAttempts = 0;
                                foreach ($resultMatrix as $result) {
                                    foreach ($result['exams'] as $exam) {
                                        if ($exam['has_attempt']) $totalAttempts++;
                                    }
                                }
                            @endphp
                            {{ $totalAttempts }}
                        </h3>
                        <p class="mb-0">Completed Results</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-danger">
                            @php
                                $totalPending = ($students->count() * $exams->count()) - $totalAttempts;
                            @endphp
                            {{ $totalPending }}
                        </h3>
                        <p class="mb-0">Pending Results</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
