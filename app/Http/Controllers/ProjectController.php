<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Project;


class ProjectController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
         'user_ids' => 'required|array',
        'user_ids.*' => 'exists:users,id',
    ]);

$project = Project::create([
    'name' => $request->name,
    'description' => $request->description,
    'created_by' => Auth::id(),
]);

// Συνδέει το project με τους users
    $project->users()->attach($request->user_ids);

    // Φορτώνουμε και τους σχετικούς χρήστες στο αποτέλεσμα
    $project->load('users');

    return response()->json([
        'message' => 'Project created successfully',
        'project' => $project,
    ], 201);
}

public function index()
{
    $user = Auth::user();
    $projects = Project::whereHas('users', function ($query) use ($user) {
        $query->where('users.id', $user->id);
    })->with('users')->get();
    return response()->json($projects);
}

public function userProjects(Request $request)
{
    $user = $request->user();

    if ($user->hasRole('superadmin')) {
        // Ο Superadmin βλέπει όλα τα projects
        $projects = Project::all();
    } else {
        // Ο απλός χρήστης βλέπει μόνο τα projects που συμμετέχει
        $projects = $user->projects; // assuming relation 'projects' exists
    }

    return response()->json(['projects' => $projects]);
}

public function show($id)
{
    $project = Project::with(['tasks', 'users', 'creator'])->find($id);

    if (!$project) {
        return response()->json(['message' => 'Project not found'], 404);
    }

    return response()->json([
        'project' => $project,
        'tasks' => $project->tasks,
        'users' => $project->users,
        'creator' => $project->creator,
    ]);
}



}
