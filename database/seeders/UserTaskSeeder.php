<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Seeder;

class UserTaskSeeder extends Seeder
{
    public function run()
    {
        // Define common names and emails
        $usersData = [
            [
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@example.com',
                'password' => bcrypt('password'), // You can use Hash::make('password') if you prefer
            ],
            [
                'name' => 'Bob Smith',
                'email' => 'bob.smith@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Charlie Brown',
                'email' => 'charlie.brown@example.com',
                'password' => bcrypt('password'),
            ],
        ];

        // Create users and their tasks
        foreach ($usersData as $userData) {
            $user = User::create($userData);
            Task::factory()->count(5)->create(['user_id' => $user->id]);
        }
    }
}