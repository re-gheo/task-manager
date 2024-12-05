<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAllTasks(): Collection
    {
        return Task::all();
    }

    public function getTaskById(int $taskId): ?Task
    {
        return Task::find($taskId);
    }

    public function createTask(array $taskDetails): Task
    {
        return Task::create($taskDetails);
    }

    public function updateTask(int $taskId, array $newDetails): bool
    {
        return Task::where('id', $taskId)->update($newDetails);
    }

    public function deleteTask(int $taskId): bool
    {
        return Task::destroy($taskId);
    }

    public function updateTaskStatus(int $taskId, string $status): bool
    {
        return Task::where('id', $taskId)->update(['status' => $status]);
    }
}
