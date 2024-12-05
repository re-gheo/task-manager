@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <h3><b>Welcome, {{ Auth::user()->name }}!</b></h3>
                        <hr>
                        <br>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card text-white bg-primary mb-3">
                                    <div class="card-header">To-Do</div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $todoCount }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-white bg-warning mb-3">
                                    <div class="card-header">In Progress</div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $inProgressCount }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-white bg-success mb-3">
                                    <div class="card-header">Done</div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $doneCount }}</h5>
                                    </div>
                                </div>
                            </div>
                            <p><b>Draft:</b>{{ $draftCount }}</p>
                        </div>


                        <p>Here are your tasks:</p>


                        <br>
                        <form method="GET" action="{{ route('home') }}" class="mb-3">
                            <div class="form-row align-items-end">
                                <div class="col">
                                    <label for="search">Search by Title:</label>
                                    <input type="text" name="search" id="search" class="form-control"
                                        value="{{ request('search') }}">
                                </div>
                                <hr>

                                <label for="per_page">Items per page:</label>
                                <select name="per_page" id="per_page" onchange="this.form.submit()">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                                </select>

                                <label for="order_by">Order by:</label>
                                <select name="order_by" id="order_by" onchange="this.form.submit()">
                                    <option value="created_at" {{ request('order_by') == 'created_at' ? 'selected' : '' }}>
                                        Date Created</option>
                                    <option value="title" {{ request('order_by') == 'title' ? 'selected' : '' }}>Title
                                    </option>
                                </select>

                                <label for="order_direction">Order direction:</label>
                                <select name="order_direction" id="order_direction" onchange="this.form.submit()">
                                    <option value="asc" {{ request('order_direction') == 'asc' ? 'selected' : '' }}>
                                        Ascending</option>
                                    <option value="desc" {{ request('order_direction') == 'desc' ? 'selected' : '' }}>
                                        Descending</option>
                                </select>

                                <label for="status">Filter by Status:</label>
                                <select name="status" id="status" onchange="this.form.submit()">
                                    <option value="">All</option>
                                    <option value="to-do" {{ request('status') == 'to-do' ? 'selected' : '' }}>To Do
                                    </option>
                                    <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>
                                        In Progress</option>
                                    <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done
                                    </option>
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
                                    <th>Image</th> <!-- New column for Image -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                    <tr>
                                        <td>
                                            <a href="{{ route('tasks.edit', $task->id) }}">
                                                {{ $task->title }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('tasks.edit', $task->id) }}">
                                                {{ $task->description }}
                                            </a>
                                        </td>
                                        <td>
                                            <form action="{{ route('tasks.updateStatus', $task->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" onchange="this.form.submit()">
                                                    <option value="to-do"
                                                        {{ $task->status == 'to-do' ? 'selected' : '' }}>To Do</option>
                                                    <option value="in-progress"
                                                        {{ $task->status == 'in-progress' ? 'selected' : '' }}>In Progress
                                                    </option>
                                                    <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>
                                                        Done</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            @if ($task->image)
                                                <img src="{{ asset('images/' . $task->image) }}" alt="Task Image"
                                                    style="max-width: 100px; max-height: 100px;">
                                            @else
                                                No Image
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-between">
                            <div>
                                Showing {{ $tasks->firstItem() }} to {{ $tasks->lastItem() }} of {{ $tasks->total() }}
                                tasks
                            </div>
                            <div>
                                {{ $tasks->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                            </div>
                        </div>

                        <hr>

                        <a href="{{ route('tasks.index') }}" class="btn btn-primary">View All Tasks</a>
                        <a href="{{ route('tasks.create') }}" class="btn btn-success">Create New Task</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
