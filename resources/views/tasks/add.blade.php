@extends("layout.home")

@section("content")
<div class="d-flex align-item-center">
    <div class="container card shodow-sm " style="margin-top: 200px ; max-width: 500px">
        <div class="fs-3 fw-bold text-center">Add new task </div>
        <form method="POST" action="{{ route('tasks.store') }}" class="mb-3">
    @csrf
    <div class="mb-3">
        <input type="text" name="title" class="form-control" >
    </div>
    <div class="mb-3">
        <input type="datetime-local" class="form-control" name="deadline">
    </div>
    <div class="mb-3">
        <textarea name="description" class="form-control" rows="3"></textarea>
    </div>
    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif
    <button class="btn btn-success rounded-pill" type="submit">Submit</button>
</form>
    </div>
</div>  
@endsection