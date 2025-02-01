<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Get All Tasks
    public function index()
    {
        // Retrieve all tasks from the database
        $tasks = Task::all();
        return response()->json($tasks, 200);
    }

    // Get Single Task
    public function show($id)
    {
        // Find the task by its ID
        $task = Task::find($id);

        // If the task does not exist, return a 404 error
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        // Return the task
        return response()->json($task, 200);
    }

    // Create Task
    public function store(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',  // Validate the due_date field
            'status' => 'required|in:pending,in-progress,completed', // Validate the status
            'employee_id' => 'required|exists:employees,id',  // Validate employee_id exists in the employees table
        ]);

        // Create a new task with validated data
        $task = Task::create($validated);

        // Return the newly created task with a 201 status code
        return response()->json($task, 201);
    }

    // Update Task
    public function update(Request $request, $id)
    {
        // Find the task by its ID
        $task = Task::find($id);

        // If the task does not exist, return a 404 error
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        // Validate incoming data for update
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',  // 'sometimes' allows it to be optional during update
            'description' => 'nullable|string',
            'due_date' => 'sometimes|required|date', // Validate due_date if provided
            'status' => 'sometimes|required|in:pending,in-progress,completed',  // Validate status if provided
            'employee_id' => 'sometimes|required|exists:employees,id',  // Validate employee_id if provided
        ]);

        // Update the task with the validated data
        $task->update($validated);

        // Return the updated task
        return response()->json($task, 200);
    }

    // Delete Task
    public function destroy($id)
    {
        // Find the task by its ID
        $task = Task::find($id);

        // If the task does not exist, return a 404 error
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        // Delete the task
        $task->delete();

        // Return a success message
        return response()->json(['message' => 'Task deleted successfully'], 200);
    }
}
