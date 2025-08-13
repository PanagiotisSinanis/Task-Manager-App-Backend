<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeEntry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Tasks;
use App\Models\Project;

class TimeEntryController extends Controller
{
    public function start(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_id'    => 'nullable|exists:tasks,id',
        ]);

        // 1. Σταματάμε οποιαδήποτε άλλη ενεργή καταγραφή για τον χρήστη.
        $activeEntry = TimeEntry::where('user_id', $user->id)
            ->whereNull('end_time')
            ->latest()
            ->first();

        if ($activeEntry) {
            $now = now();
            // Υπολογίζουμε τον χρόνο που πέρασε από την έναρξη της τρέχουσας συνεδρίας.
            $secondsWorkedNow = Carbon::parse($activeEntry->current_start_time)->diffInSeconds($now);

            // Προσθέτουμε τον χρόνο που μόλις δουλέψαμε στη συνολική διάρκεια.
            $activeEntry->duration = ($activeEntry->duration ?? 0) + $secondsWorkedNow;
            $activeEntry->end_time = $now;
            $activeEntry->current_start_time = null; // Μηδενίζουμε το current_start_time
            $activeEntry->save();
        }

        // 2. Βρίσκουμε την πιο πρόσφατη ολοκληρωμένη καταγραφή για αυτήν την εργασία.
        $latestEntryForTask = TimeEntry::where('user_id', $user->id)
            ->where('task_id', $validated['task_id'])
            ->latest('created_at') // Αλλάξαμε σε created_at για πιο αξιόπιστη σειρά
            ->first();

        if ($latestEntryForTask) {
            // Αν υπάρχει, την "επανεκκινούμε" ενημερώνοντας μόνο την ώρα έναρξης της τρέχουσας συνεδρίας.
            $latestEntryForTask->current_start_time = now();
            $latestEntryForTask->end_time = null;
            $latestEntryForTask->save();

            return response()->json(['entry' => $latestEntryForTask->fresh()], 201);
        } else {
            // Αν δεν υπάρχει, δημιουργούμε μια νέα καταγραφή.
            $now = now();
            $entry = TimeEntry::create([
                'user_id' => $user->id,
                'project_id' => $validated['project_id'],
                'task_id' => $request->task_id,
                'start_time' => $now,
                'current_start_time' => $now,
                'duration' => 0,
            ]);

            return response()->json(['entry' => $entry], 201);
        }
    }

    public function quickEntry(Request $request)
    {
        $request->validate([
            'project_id' => 'required|integer|exists:projects,id',
            'description' => 'nullable|string|max:255',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
        ]);

        try {
            $user = Auth::user();
            $today = Carbon::today()->toDateString();

            $start = Carbon::parse("{$today} {$request->start_time}");
            $end = Carbon::parse("{$today} {$request->end_time}");

            if ($end->lt($start)) {
                return response()->json(['error' => 'End time must be after start time'], 422);
            }

            $duration = $end->diffInSeconds($start);

            $entry = TimeEntry::create([
                'user_id' => $user->id,
                'project_id' => $request->project_id,
                'description' => $request->description,
                'start_time' => $start,
                'end_time' => $end,
                'duration' => $duration,
            ]);

            return response()->json([
                'message' => 'Quick time entry saved!',
                'entry' => $entry,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    public function index(Request $request)
    {
        $entries = TimeEntry::where('user_id', Auth::id())
            ->with('project')
            ->orderByDesc('start_time')
            ->get();

        return response()->json($entries);
    }

    public function totalTimePerProject($projectId)
    {
        $userId = Auth::id();

        $total = TimeEntry::where('user_id', $userId)
            ->where('project_id', $projectId)
            ->sum('duration');

        return response()->json([
            'project_id' => $projectId,
            'total_seconds' => $total,
            'formatted' => gmdate('H:i:s', $total),
        ]);
    }

    public function active()
    {
        $userId = Auth::id();

        $entry = TimeEntry::where('user_id', $userId)
            ->whereNull('end_time')
            ->latest()
            ->first();

        if (!$entry) {
            return response()->json(['active' => false]);
        }

        return response()->json([
            'active' => true,
            'entry' => $entry
        ]);
    }

    public function entries(Request $request)
    {
        try {
            $entries = TimeEntry::with(['project', 'task'])
                ->where('user_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'entries' => $entries,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch time entries',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $entry = TimeEntry::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $entry->delete();

            return response()->json(['message' => 'Time entry deleted successfully!'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Time entry not found or unauthorized.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete time entry.', 'message' => $e->getMessage()], 500);
        }
    }

    public function stop(Request $request)
    {
        $userId = Auth::id();
        $entry = TimeEntry::where('user_id', $userId)
            ->whereNull('end_time')
            ->latest()
            ->first();

        if (!$entry) {
            return response()->json(['error' => 'No active entry found'], 404);
        }

        try {
            $endTime = now();
            // Υπολογίζουμε τον χρόνο που πέρασε από την έναρξη της τρέχουσας συνεδρίας
            $secondsWorkedNow = Carbon::parse($entry->current_start_time)->diffInSeconds($endTime);

            // Προσθέτουμε τον χρόνο που μόλις δουλέψαμε στη συνολική διάρκεια
            $entry->duration = ($entry->duration ?? 0) + $secondsWorkedNow;
            $entry->end_time = $endTime;
            $entry->current_start_time = null; // Μηδενίζουμε το current_start_time
            $entry->save();

            if ($entry->task_id) {
                $task = Tasks::find($entry->task_id);
                if ($task) {
                    $task->end_time = $entry->end_time;
                    $task->status = 'pending';
                    $task->save();
                }
            }

            $entry->refresh()->load('task', 'project');

            return response()->json([
                'message' => 'Time entry stopped!',
                'entry' => $entry
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
