@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Form Teacher Banner -->
    @if($isFormTeacher ?? false)
    <div class="bg-gradient-to-r from-green-600 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold mb-2">Welcome, Form Teacher! 👨‍🏫</h3>
                <p class="text-white/90">You are assigned as a form teacher. Access your class dashboard to manage students and compile results.</p>
            </div>
            <a href="{{ route('teacher.form-teacher.dashboard') }}" 
               class="bg-white text-indigo-600 hover:bg-indigo-50 px-8 py-3 rounded-lg font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all whitespace-nowrap ml-4">
                📚 Go to Form Teacher Dashboard
            </a>
        </div>
    </div>
    @endif

    <!-- Welcome Header with Background -->
    <div class="relative bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-3xl shadow-2xl overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
        
        <div class="relative p-8 text-white">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-4xl font-bold mb-2">Welcome, {{ Auth::user()->name }}! 👨‍💼</h1>
                    <p class="text-white/90 text-lg">Administrator Dashboard</p>
                    <p class="text-white/70 text-sm mt-2 italic">Cambridge International School - CBT System</p>
                </div>
                <div class="bg-white/20 backdrop-blur-md rounded-2xl p-6 border border-white/30">
                    <div class="text-center">
                        <div class="text-5xl font-bold">{{ \Carbon\Carbon::now()->format('d') }}</div>
                        <div class="text-sm mt-1">{{ \Carbon\Carbon::now()->format('F Y') }}</div>
                        <div class="text-xs mt-2 opacity-80">{{ \Carbon\Carbon::now()->format('l') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Exams -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <div class="text-4xl font-bold">{{ $examsCount }}</div>
                </div>
            </div>
            <div class="text-white/90 font-semibold text-lg">Total Exams</div>
            <div class="text-white/70 text-sm mt-1">All exams in system</div>
        </div>

        <!-- Total Students -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <div class="text-4xl font-bold">{{ $studentsCount }}</div>
                </div>
            </div>
            <div class="text-white/90 font-semibold text-lg">Total Students</div>
            <div class="text-white/70 text-sm mt-1">Registered students</div>
        </div>

        <!-- Recent Attempts -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <div class="text-4xl font-bold">{{ $recentAttempts->count() }}</div>
                </div>
            </div>
            <div class="text-white/90 font-semibold text-lg">Recent Attempts</div>
            <div class="text-white/70 text-sm mt-1">Latest submissions</div>
        </div>
    </div>

    <!-- Quick Actions & Recent Exams -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 flex items-center">
                    <span class="mr-2">⚡</span> Quick Actions
                </h3>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('admin.exam.create') }}" 
                   class="flex items-center justify-between bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all group">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create New Exam
                    </span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <a href="{{ route('admin.exams') }}" 
                   class="flex items-center justify-between bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all group">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        View All Exams
                    </span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <a href="{{ route('admin.results.index') }}" 
                   class="flex items-center justify-between bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-6 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all group">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Results Portal
                    </span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.students') }}" 
                   class="flex items-center justify-between bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white px-6 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all group">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Manage Students
                    </span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <a href="{{ route('admin.teachers') }}" 
                   class="flex items-center justify-between bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white px-6 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all group">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Manage Teachers
                    </span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <a href="{{ route('admin.classes') }}" 
                   class="flex items-center justify-between bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-6 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all group">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Manage Classes
                    </span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <a href="{{ route('admin.subjects.index') }}" 
                   class="flex items-center justify-between bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-6 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all group">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17.25m20-11.002c0 5.159-4.592 9.513-10 9.513m10-9.513c5.05 0 9.233 4.233 10 9.513M12 19.25m0 0c-5.408 0-10-4.354-10-9.513"></path>
                        </svg>
                        Manage Subjects
                    </span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <a href="{{ route('admin.form-teachers.index') }}" 
                   class="flex items-center justify-between bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white px-6 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all group">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Manage Form Teachers
                    </span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                @endif
            </div>
        </div>

        <!-- Recent Exams -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 flex items-center">
                    <span class="mr-2">📚</span> Recent Exams
                </h3>
            </div>
            <div class="p-6">
                @forelse($recentExams as $exam)
                <div class="border-l-4 border-green-500 bg-green-50 rounded-r-xl pl-4 py-3 mb-3 hover:bg-green-100 transition-colors">
                    <p class="font-bold text-gray-800">{{ $exam->title }}</p>
                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-semibold">
                            {{ $exam->subject }}
                        </span>
                        <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full font-semibold">
                            📝 {{ $exam->questions->count() }} questions
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="text-5xl mb-3">📭</div>
                    <p class="text-gray-500">No exams yet</p>
                    <a href="{{ route('admin.exam.create') }}" class="text-green-600 hover:text-green-700 font-semibold text-sm mt-2 inline-block">
                        Create your first exam →
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Student Attempts Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 px-6 py-4 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                <span class="mr-2">📊</span> Recent Student Attempts
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Exam</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Score</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentAttempts as $attempt)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold text-gray-800">{{ $attempt->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $attempt->user->registration_number }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $attempt->exam->title }}</div>
                            <div class="text-xs text-gray-500">{{ $attempt->exam->subject }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($attempt->status === 'graded')
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full font-semibold">✓ Graded</span>
                            @elseif($attempt->status === 'submitted')
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-semibold">⏳ Pending</span>
                            @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs rounded-full font-semibold">⚪ In Progress</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($attempt->total_score !== null)
                            <span class="font-bold text-gray-800">{{ $attempt->total_score }}</span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $attempt->created_at->format('d M Y') }}
                            <div class="text-xs text-gray-400">{{ $attempt->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($attempt->status === 'submitted')
                            <a href="{{ route('admin.attempt.grade', $attempt->id) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-semibold inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Grade
                            </a>
                            @else
                            <a href="{{ route('admin.exam.results', $attempt->exam_id) }}" 
                               class="text-gray-600 hover:text-gray-800 font-semibold text-sm">
                                View →
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-5xl mb-3">📝</div>
                            <p class="text-gray-500 text-lg">No attempts yet</p>
                            <p class="text-gray-400 text-sm mt-1">Student submissions will appear here</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection