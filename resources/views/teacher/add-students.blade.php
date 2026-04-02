@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-2">Add Students to {{ $class->name }}</h2>
            <p class="text-muted">Manage students in your assigned class</p>
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

    <div class="row">
        <!-- Add New Student -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Add Student to {{ $class->name }}</h5>
                </div>
                <div class="card-body">
                    @if ($availableStudents->isEmpty())
                        <div class="alert alert-info">
                            All available students have already been added to this class.
                        </div>
                    @else
                        <form action="{{ route('teacher.form-teacher.store-student', $class->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="student_id" class="form-label">Select Student</label>
                                <select class="form-select @error('student_id') is-invalid @enderror" 
                                    id="student_id" name="student_id" required>
                                    <option value="">-- Choose a student --</option>
                                    @foreach ($availableStudents as $student)
                                        <option value="{{ $student->id }}">
                                            {{ $student->name }} ({{ $student->registration_number }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-plus"></i> Add Student
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Current Students -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Students in {{ $class->name }}
                        @if ($studentsInClass)
                            <span class="badge bg-light text-dark ms-2">{{ count($studentsInClass) }}</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if ($studentsInClass && count($studentsInClass) > 0)
                        <div class="list-group">
                            @php
                                $classStudents = \App\Models\User::whereIn('id', $studentsInClass)->orderBy('name')->get();
                            @endphp
                            @foreach ($classStudents as $student)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{ $student->name }}</h6>
                                        <small class="text-muted">{{ $student->registration_number }}</small>
                                    </div>
                                    <form action="{{ route('teacher.form-teacher.remove-student', [$class->id, $student->id]) }}" 
                                        method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to remove this student?');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            No students have been added to this class yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-4">
        <a href="{{ route('teacher.form-teacher.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>
@endsection
