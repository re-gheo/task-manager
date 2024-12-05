@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">


        <div class="col-md-8">
            <div class="card">
                <div class="container">
                    <h1>Create Task</h1>
                    <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">

                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" required></textarea>
                        </div>


                        <div>
                            <label for="publish_status">Publish Status:</label>
                            <select name="publish_status" id="publish_status">
                                <option value="draft"
                                    {{ old('publish_status', $task->publish_status ?? '') == 'draft' ? 'selected' : '' }}>
                                    Draft</option>
                                <option value="published"
                                    {{ old('publish_status', $task->publish_status ?? '') == 'published' ? 'selected' : '' }}>
                                    Published</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="image">Attach Image</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <!-- File input for image -->
                        </div>

                        <button type="submit" class="btn btn-success">Create Task</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
