<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    // GENERATED EXAMPLE HERE FOR RANDOME TESTING
    protected $tasks = [
        [
            'title' => 'Buy groceries',
            'description' => 'Pick up milk, eggs, and bread from the store.',
        ],
        [
            'title' => 'Walk the dog',
            'description' => 'Take the dog for a 30-minute walk in the park.',
        ],
        [
            'title' => 'Read a book',
            'description' => 'Finish reading the current chapter by tonight.',
        ],
        [
            'title' => 'Clean the house',
            'description' => 'Tidy up the living room, kitchen, and bathroom.',
        ],
        [
            'title' => 'Finish homework',
            'description' => 'Complete math and science assignments due tomorrow.',
        ],
        [
            'title' => 'Call mom',
            'description' => 'Check in with mom and see how she is doing.',
        ],
        [
            'title' => 'Pay bills',
            'description' => 'Make sure to pay the electricity and water bills online.',
        ],
        [
            'title' => 'Go for a run',
            'description' => 'Run for at least 20 minutes in the neighborhood.',
        ],
    ];

    public function definition()
    {
        $task = Arr::random($this->tasks);
        
        // Define possible values for status and publish_status
        $publishStatusOptions = ['published', 'draft'];
        $publishStatus = Arr::random($publishStatusOptions);
        
        // Set status based on publish_status
        if ($publishStatus === 'draft') {
            $status = 'to-do'; // Default status for draft
        } else {
            // If published, choose a random status from the allowed options
            $statusOptions = ['to-do', 'in-progress', 'done'];
            $status = Arr::random($statusOptions);
        }
    
        return [
            'title' => $task['title'],
            'description' => $task['description'],
            'user_id' => null,
            'status' => $status,
            'publish_status' => $publishStatus,
        ];
    }
}