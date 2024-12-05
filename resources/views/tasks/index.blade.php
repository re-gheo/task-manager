@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Tasks') }}</div>

                <div class="card-body">
                    <a href="{{ route('home') }}"><button class="btn btn-primary">Back Home</button></a>
                    <form method="GET" action="{{ route('tasks.index') }}" class="mb-3">
                        <div class="form-row align-items-end">
                            <div class="col">
                                <label for="search">Search by Title:</label>
                                <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}">
                            </div>
                            <hr>
                            <label for="per_page">Items per page:</label>
                            <select name="per_page" id="per_page" onchange="this.form.submit()">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                            </select>
    
                            <label for="order_by">Order by:</label>
                            <select name="order_by" id="order_by" onchange="this.form.submit()">
                                <option value="created_at" {{ request('order_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                                <option value="title" {{ request('order_by') == 'title' ? 'selected' : '' }}>Title</option>
                            </select>
    
                            <label for="order_direction">Order direction:</label>
                            <select name="order_direction" id="order_direction" onchange="this.form.submit()">
                                <option value="asc" {{ request('order_direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                <option value="desc" {{ request('order_direction') == 'desc' ? 'selected' : '' }}>Descending</option>
                            </select>
                            <label for="status">Filter by Status:</label>
                            <select name="status" id="status"  onchange="this.form.submit()">
                                <option value="">All</option>
                                <option value="to-do" {{ request('status') == 'to-do' ? 'selected' : '' }}>To Do</option>
                                <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary mt-4">Search</button>
                            </div>
                        </div>
                    </form>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Actions</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <td>{{ $task->title }}
                                        @if($task->publish_status === 'draft')
                                        <b>(Draft)</b>
                                    @else
                                       
                                    @endif

                                    </td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->status }}</td>
                                    <td>
                                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style=" display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between">
                        <div>
                            Showing {{ $tasks->firstItem() }} to {{ $tasks->lastItem() }} of {{ $tasks->total() }} tasks
                        </div>
                        <div>
                            {{ $tasks->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>

                    <hr>

                    <a href="{{ route('tasks.create') }}" class="btn btn-success">Create New Task</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection