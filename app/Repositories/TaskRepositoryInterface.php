<?php


namespace App\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function getAllTasks(): Collection;
    public function getTaskById(int $taskId): ?Task;
    public function createTask(array $taskDetails): Task;
    public function updateTask(int $taskId, array $newDetails): bool;
    public function deleteTask(int $taskId): bool;

    public function updateTaskStatus(int $taskId, string $status): bool;
}
