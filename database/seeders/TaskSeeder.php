<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run()
    {
        
        $employee1 = Employee::firstOrCreate([
            'name' => 'Employee 1',
            'email' => 'employee1@example.com',
            'phone' => '1234567890',
        ]);

        $employee2 = Employee::firstOrCreate([
            'name' => 'Employee 2',
            'email' => 'employee2@example.com',
            'phone' => '1234567891',
        ]);

        $employee3 = Employee::firstOrCreate([
            'name' => 'Employee 3',
            'email' => 'employee3@example.com',
            'phone' => '1234567892',
        ]);

        
        $employee1->tasks()->create([
            'title' => 'Task 1',
            'description' => 'Description for task 1',
            'due_date' => now()->addDays(7),
            'status' => 'pending',
        ]);

        $employee1->tasks()->create([
            'title' => 'Task 2',
            'description' => 'Description for task 2',
            'due_date' => now()->addDays(14),
            'status' => 'in_progress',
        ]);

        $employee2->tasks()->create([
            'title' => 'Task 3',
            'description' => 'Description for task 3',
            'due_date' => now()->addDays(7),
            'status' => 'completed',
        ]);

        $employee2->tasks()->create([
            'title' => 'Task 4',
            'description' => 'Description for task 4',
            'due_date' => now()->addDays(5),
            'status' => 'pending',
        ]);

        $employee3->tasks()->create([
            'title' => 'Task 5',
            'description' => 'Description for task 5',
            'due_date' => now()->addDays(10),
            'status' => 'in_progress',
        ]);
    }
}
