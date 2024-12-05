<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class TaskController extends Controller
{

    /**
     * Display a listing of the tasks.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {


        $perPage = $request->input('per_page', 10);
        $orderBy = $request->input('order_by', 'created_at');
        $orderDirection = $request->input('order_direction', 'asc');

        $search = $request->input('search', '');

        $status = $request->input('status', '');

        if (!in_array($orderBy, ['title', 'created_at'])) {
            $orderBy = 'created_at';
        }

        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'asc';
        }

        $tasks = Task::where('user_id', auth()->id())
            ->when($search, function ($query) use ($search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy($orderBy, $orderDirection)
            ->paginate($perPage);

        return view('tasks.index', compact('tasks'));
    }



    /**
     * Show the form for creating a new task.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('tasks.create');
    }




    /**
     * Store a newly created task in storage.
     *
     * @param  \App\Http\Requests\TaskRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TaskRequest $request)
    {



        $taskData = [
            'title' => $request->title,
            'description' => $request->description,
            'publish_status' => $request->publish_status,
            'user_id' => Auth::id(),
        ];



        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);
            $taskData['image'] = $imageName;
        }

        $new_task = Task::create($taskData);


        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }


    /**
     * Show the form for editing the specified task.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            return redirect()->route('tasks.index')->with('error', 'Unauthorized access to this task.');
        }
        return view('tasks.edit', compact('task'));
    }










    /**
     * Update the specified task in storage.
     *
     * @param  \App\Http\Requests\TaskRequest  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TaskRequest $request, Task $task)
    {

        $task->update($request->only('title', 'description', 'publish_status'));

        if ($request->hasFile('image')) {

            if ($task->image) {
                File::delete(public_path('images/' . $task->image));
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $task->image = $imageName;
        }


        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }





    /**
     * Remove the specified task from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }




    /**
     * Update the status of the specified task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {

        $request->validate([
            'status' => 'required|in:to-do,in-progress,done',
        ]);


        $task = Task::findOrFail($id);


        $task->status = $request->status;

        $task->save();


        return redirect()->back()->with('status', 'Task status updated successfully to "' . $request->status . '"!');
    }
}
