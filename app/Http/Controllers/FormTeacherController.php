<?php

namespace App\Http\Controllers;

use App\Models\FormTeacher;
use App\Models\User;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class FormTeacherController extends Controller
{
    /**
     * Display a listing of form teacher assignments
     */
    public function index()
    {
        $formTeachers = FormTeacher::with(['teacher', 'schoolClass'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.form-teachers.index', compact('formTeachers'));
    }

    /**
     * Show the form for creating a new form teacher assignment
     */
    public function create()
    {
        // Get classes that don't have a form teacher assigned
        $unassignedClasses = SchoolClass::whereDoesntHave('formTeacher')
            ->orderBy('name')
            ->get();

        // Get all teachers
        $teachers = User::where('role', 'teacher')
            ->orderBy('name')
            ->get();

        return view('admin.form-teachers.create', compact('unassignedClasses', 'teachers'));
    }

    /**
     * Store a newly created form teacher assignment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:school_classes,id|unique:form_teachers,class_id',
        ], [
            'class_id.unique' => 'This class already has a form teacher assigned.',
        ]);

        $validated['assigned_date'] = now();
        $validated['is_active'] = true;

        FormTeacher::create($validated);

        return redirect()->route('admin.form-teachers.index')
            ->with('success', 'Form teacher assigned successfully!');
    }

    /**
     * Display the specified form teacher assignment
     */
    public function show(FormTeacher $formTeacher)
    {
        $formTeacher->load(['teacher', 'schoolClass']);

        return view('admin.form-teachers.show', compact('formTeacher'));
    }

    /**
     * Show the form for editing the specified form teacher assignment
     */
    public function edit(FormTeacher $formTeacher)
    {
        $formTeacher->load(['teacher', 'schoolClass']);

        // Get all teachers
        $teachers = User::where('role', 'teacher')
            ->orderBy('name')
            ->get();

        return view('admin.form-teachers.edit', compact('formTeacher', 'teachers'));
    }

    /**
     * Update the specified form teacher assignment
     */
    public function update(Request $request, FormTeacher $formTeacher)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $formTeacher->update($validated);

        return redirect()->route('admin.form-teachers.index')
            ->with('success', 'Form teacher assignment updated successfully!');
    }

    /**
     * Remove the specified form teacher assignment (soft delete or just delete)
     */
    public function destroy(FormTeacher $formTeacher)
    {
        $className = $formTeacher->schoolClass->name;
        
        $formTeacher->delete();

        return redirect()->route('admin.form-teachers.index')
            ->with('success', "Form teacher removed from {$className}!");
    }
}
