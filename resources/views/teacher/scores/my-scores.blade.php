@extends('layouts.app')

@section('title', 'My Submitted Scores')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">📖 My Submitted Scores</h1>
        <p class="text-gray-600">View all scores you've entered and submitted</p>
    </div>

    <!-- Navigation -->
    <div class="mb-6 flex gap-3">
        <a href="{{ route('teacher.scores.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
            ← Back to Dashboard
        </a>
    </div>

    <!-- Session/Term Filter -->
    @if($activeSession && $activeTerm)
    <div class="bg-blue-50 border border-blue-300 rounded-lg p-4 mb-6">
        <p class="text-blue-800">
            <strong>Viewing:</strong> {{ $activeSession->name ?? 'N/A' }} - {{ $activeTerm->name ?? 'N/A' }}
        </p>
    </div>
    @endif

    <!-- Scores Table -->
    @if($scores->isEmpty())
        <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-8 text-center">
            <p class="text-yellow-800 font-semibold text-lg">📭 No submitted scores yet</p>
            <p class="text-yellow-700 mt-2">Start entering and submitting scores from the dashboard</p>
            <a href="{{ route('teacher.scores.select') }}" class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold">
                Begin Entering Scores
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left font-bold">Student Name</th>
                        <th class="px-6 py-4 text-left font-bold">Registration #</th>
                        <th class="px-6 py-4 text-left font-bold">Subject</th>
                        <th class="px-6 py-4 text-left font-bold">Class</th>
                        <th class="px-6 py-4 text-center font-bold">CA1</th>
                        <th class="px-6 py-4 text-center font-bold">CA2</th>
                        <th class="px-6 py-4 text-center font-bold">CA3</th>
                        <th class="px-6 py-4 text-center font-bold">Exam</th>
                        <th class="px-6 py-4 text-center font-bold">Total</th>
                        <th class="px-6 py-4 text-center font-bold">Status</th>
                        <th class="px-6 py-4 text-center font-bold">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($scores as $score)
                        @php
                            $ca_total = ($score->ca1 ?? 0) + ($score->ca2 ?? 0) + ($score->ca3 ?? 0);
                            $total = $ca_total + ($score->exam ?? 0);
                            $status_color = $score->status === 'submitted' ? 'bg-green-100 text-green-800' : ($score->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800');
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $score->student->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $score->student->registration_number ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $score->subject->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $score->class->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-center font-bold text-blue-600">{{ $score->ca1 ?? '-' }}</td>
                            <td class="px-6 py-4 text-center font-bold text-blue-600">{{ $score->ca2 ?? '-' }}</td>
                            <td class="px-6 py-4 text-center font-bold text-blue-600">{{ $score->ca3 ?? '-' }}</td>
                            <td class="px-6 py-4 text-center font-bold text-green-600">{{ $score->exam ?? '-' }}</td>
                            <td class="px-6 py-4 text-center font-bold text-purple-600 bg-gray-50">{{ $total }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-bold {{ $status_color }}">
                                    {{ ucfirst($score->status ?? 'unknown') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600">{{ $score->updated_at->format('d M Y H:i') ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($scores->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $scores->links() }}
            </div>
        @endif

        <!-- Summary -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white">
                <p class="text-blue-100 text-sm">Total Scores Entered</p>
                <p class="text-3xl font-bold">{{ $scores->total() }}</p>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow p-6 text-white">
                <p class="text-green-100 text-sm">Submitted</p>
                <p class="text-3xl font-bold">{{ $scores->count() }}</p>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow p-6 text-white">
                <p class="text-purple-100 text-sm">Subjects</p>
                <p class="text-3xl font-bold">{{ $scores->pluck('subject_id')->unique()->count() }}</p>
            </div>
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow p-6 text-white">
                <p class="text-orange-100 text-sm">Students</p>
                <p class="text-3xl font-bold">{{ $scores->pluck('student_id')->unique()->count() }}</p>
            </div>
        </div>
    @endif
</div>

@endsection
