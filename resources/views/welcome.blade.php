@extends("layout.home")

@section("title", "Task List")

@section("content")
<main class="flex-shrink-0 mt-5 pt-5">
    <div class="container" style="max-width: 900px;">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif

        {{-- Search & Filter Form --}}
        <form action="{{ route('tasks.index') }}" method="GET" class="mb-4 my-4 d-flex gap-2">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="form-control"
                placeholder="Search by title or description..."
                aria-label="Search tasks"
            >
            <select name="status" class="form-select" style="max-width: 150px;">
                <option value="" {{ request('status') == '' ? 'selected' : '' }}>All</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        {{-- Tasks Table --}}
        <table id="tasks-table" class="display table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Deadline</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->description }}</td>
                    <td>
                        @if($task->status === 'completed')
                            <span class="badge bg-success">Completed</span>
                        @elseif($task->status === 'pending' || is_null($task->status))
                            <span class="badge bg-warning text-dark">Pending</span>
                        @else
                            <span class="badge bg-secondary">Unknown</span>
                        @endif
                    </td>
                    <td>{{ $task->created_at->diffForHumans() }}</td>
                    <td>{{ $task->deadline ? $task->deadline->format('Y-m-d H:i') : '-' }}</td>
                    <td>
                        @if($task->status !== 'completed')
                        <form method="POST" action="{{ route('tasks.update', $task->id) }}" style="display:inline">
                            @csrf
                            @method('PUT')
                            <button class="btn btn-sm btn-success" type="submit" title="Complete Task">Complete</button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')" title="Delete Task">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</main>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#tasks-table').DataTable({
        order: [[3, 'desc']], // Sort by Created At (4th column, index 3) descending
        pageLength: 10,
        lengthChange: false,
        language: {
            search: "Search tasks:",
            emptyTable: "No tasks found"
        }
    });
});
</script>
@endsection
