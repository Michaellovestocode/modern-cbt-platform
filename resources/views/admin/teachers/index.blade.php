@extends('layouts.app')

@section('title', 'Manage Teachers')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Manage Teachers</h2>
        <a href="{{ route('admin.teacher.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold">
            + Add New Teacher
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Staff ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned Subjects</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exams Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($teachers as $index => $teacher)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $teacher->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $teacher->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $teacher->registration_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($teacher->subjects->count() > 0)
                            <div class="flex flex-wrap gap-1">
                                @foreach($teacher->subjects as $subject)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $subject->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-400 text-sm">No subjects assigned</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $teacher->exams->count() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.subjects.assign-subjects', $teacher->id) }}" 
                               class="text-purple-600 hover:text-purple-800">Subjects</a>
                            <a href="{{ route('admin.teacher.edit', $teacher->id) }}" 
                               class="text-blue-600 hover:text-blue-800">Edit</a>
                            <form action="{{ route('admin.teacher.delete', $teacher->id) }}" method="POST" 
                                  onsubmit="return confirm('Delete this teacher?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        No teachers yet. Click "Add New Teacher" to get started.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection