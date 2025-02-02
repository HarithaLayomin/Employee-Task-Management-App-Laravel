<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{
    // Get all tasks with employee details
    public function index()
    {
        return response()->json(Task::with('employee')->get(), 200);
        
    }

    // Create a new task
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed',
            'employee_id' => 'required|exists:employees,id',
        ]);

        $task = Task::create($validatedData);

        return response()->json($task, 201);
    }

    // Get a single task
    public function show($id)
    {
        $task = Task::with('employee')->find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json($task);
    }

    // Update a task
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'due_date' => 'sometimes|date',
            'status' => 'sometimes|in:pending,in_progress,completed',
            'employee_id' => 'sometimes|exists:employees,id',
        ]);

        $task->update($validatedData);

        return response()->json($task);
    }

    // Delete a task
    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function getAssignedTasks(Request $request)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);  // Unauthorized if the user is not logged in
        }
    
        // Get the logged-in user's ID
        $userId = Auth::user()->id;
    
        // Get the tasks assigned to the logged-in user
        $tasks = Task::where('assigned_employee', $userId)  // Assuming 'assigned_employee' is the column in the tasks table storing employee ID
                    ->get(['title', 'description', 'status']);  // Adjust the fields as needed
    
        // Return the tasks as a JSON response
        return response()->json($tasks);
    }
    
}
