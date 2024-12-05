<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
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
            ->where('publish_status', 'published')
            ->when($search, function ($query) use ($search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy($orderBy, $orderDirection)
            ->paginate($perPage);
        $todoCount = Task::where('user_id',  auth()->id())->where('status', 'to-do')->where('publish_status', 'published')->count();
        $inProgressCount = Task::where('user_id',  auth()->id())->where('status', 'in-progress')->count();
        $doneCount = Task::where('user_id',  auth()->id())->where('status', 'done')->count();



        $draftCount = Task::where('user_id',  auth()->id())->where('publish_status', 'draft')->count();


        return view('home', compact('tasks', 'todoCount', 'inProgressCount', 'doneCount', 'draftCount'));
    }
}
