<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Repositories\TaskRepositoryInterface;

class TaskController extends Controller
{

    protected $taskRepository;
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Display a listing of the tasks.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
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

            $tasks = $user->tasks()
                ->when($search, function ($query) use ($search) {
                    return $query->where('title', 'like', '%' . $search . '%');
                })
                ->when($status, function ($query) use ($status) {
                    return $query->where('status', $status);
                })
                ->orderBy($orderBy, $orderDirection)
                ->paginate($perPage);

            return view('tasks.index', compact('tasks'));
        } catch (\Exception $e) {
            Log::error('Error fetching tasks: ' . $e->getMessage());
            return view('tasks.index')->with('error', 'An error occurred while fetching tasks.');
        }
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
        DB::beginTransaction();
        try {
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

            $new_task = $this->taskRepository->createTask($taskData);

            DB::commit();
            return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create task: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create task. Please try again.');
        }
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
        DB::beginTransaction();
        try {
            $taskData = $request->only('title', 'description', 'publish_status');

            if ($request->hasFile('image')) {
                if ($task->image) {
                    File::delete(public_path('images/' . $task->image));
                }

                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $imageName);
                $taskData['image'] = $imageName;
            }

            $this->taskRepository->updateTask($task->id, $taskData);

            DB::commit();
            return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update task: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update task. Please try again.');
        }
    }





    /**
     * Remove the specified task from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task)
    {
        try {
            $this->taskRepository->deleteTask($task->id);
            return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete task: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete task. Please try again.');
        }
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
        DB::beginTransaction();
        try {
            $request->validate([
                'status' => 'required|in:to-do,in-progress,done',
            ]);

            $updated = $this->taskRepository->updateTaskStatus($id, $request->status);

            if ($updated) {
                DB::commit();
                return redirect()->back()->with('status', 'Task status updated successfully to "' . $request->status . '"!');
            } else {
                throw new \Exception('Failed to update task status.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update task status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating task status.');
        }
    }
}
