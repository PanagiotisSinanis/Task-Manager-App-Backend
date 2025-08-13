<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use Illuminate\Http\Request;
use App\Models\Tasks;

class TaskManager extends Controller
{
    // GET /tasks
    public function index(Request $request)
    {
        $query = Tasks::query();

        // Φιλτράρισμα με βάση status (pending/completed)
        if ($request->filled('status') && in_array($request->status, ['pending', 'completed'])) {
            if ($request->status === 'pending') {
                $query->where('status', 'pending');
            }
        }

        // Φιλτράρισμα με βάση αναζήτηση σε title ή description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Φιλτράρισμα με βάση τίτλο
        if ($request->filled('title')) {
            $title = $request->title;
            $query->where('title', 'like', "%{$title}%");
        }

        // Φιλτράρισμα με βάση ημερομηνία
        if ($request->filled('date')) {
            $date = $request->date;
            $query->whereDate('created_at', $date);
        }

        //  Εδώ προσθέτεις το eager loading του project
        $tasks = $query->with('project')->orderBy('created_at', 'desc')->get();

        if ($request->wantsJson()) {
            return response()->json($tasks);
        }

        return view("welcome", compact('tasks'));
    }

    // GET /tasks/create
    public function create()
    {
        return view('tasks.add');
    }

    // POST /tasks
    public function store(StoreTaskRequest $request)
    {
        try {
            $data = $request->validated();
            $data['status'] = 'pending';

            $user = $request->user();

            // Έλεγχος αν ο χρήστης ανήκει στο project
            $belongs = $user->projects()->where('projects.id', $data['project_id'])->exists();

            if (!$belongs) {
                if ($request->wantsJson()) {
                    return response()->json(['error' => 'Unauthorized: You do not belong to this project'], 403);
                }
                return redirect()->back()->with('error', 'You are not authorized to add tasks to this project');
            }

            $task = Tasks::create($data);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Task created successfully',
                    'task' => $task
                ], 201);
            }

            return redirect()->route('tasks.index')->with("success", "Task added successfully");
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Failed to create task'], 500);
            }

            return redirect()->route('tasks.create')->with("error", "Failed to add task");
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $task = Tasks::findOrFail($id);

            // (Προαιρετικά): Έλεγχος αν ο χρήστης ανήκει στο project του task
            $user = $request->user();
            $belongs = $user->projects()->where('projects.id', $task->project_id)->exists();

            if (!$belongs) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Αν έχει time entries που σχετίζονται, πρέπει να διαγραφούν πρώτα
            if (method_exists($task, 'timeEntries')) {
                $task->timeEntries()->delete();
            }

            $task->delete();

            return response()->json(['message' => 'Task deleted'], 200);
        } catch (\Exception $e) {
            // Αυτό θα σου εμφανίσει ακριβώς το error στο frontend
            return response()->json([
                'error' => 'Task not deleted',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }


    // PUT /tasks/{id}
    public function update(Request $request, $id)
    {
        $task = Tasks::findOrFail($id);
        $task->status = 'completed';

        if ($task->save()) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Task completed', 'task' => $task], 200);
            }
            return redirect()->route('tasks.index')->with("success", "Task completed");
        }

        if ($request->wantsJson()) {
            return response()->json(['error' => 'Task not completed'], 500);
        }
        return redirect()->route('tasks.index')->with("error", "Task not completed");
    }

    // GET /tasks/project/{id}
    public function getTasksByProject($id)
    {
        try {
            // Μπορείς να βάλεις with('project') εδώ αν χρειάζεται επίσης
            $tasks = Tasks::where('project_id', $id)->with('project')->get();
            return response()->json(['tasks' => $tasks]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function restart(Request $request, $id)
    {
        try {
            $task = Tasks::findOrFail($id);

            $now = now();
            $task->start_time = $now;
            $task->end_time = $now;
            $task->status = 'pending';
            $task->save();

            return response()->json(['message' => 'Task restarted', 'task' => $task]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to restart task'], 500);
        }
    }
}
