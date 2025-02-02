<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class TopEmployeeController extends Controller
{
    public function index()
    {
        // Fetch top 5 employees with the most completed tasks
        $topEmployees = Employee::withCount(['tasks' => function ($query) {
            $query->where('status', 'completed');
        }])
        ->orderByDesc('tasks_count')
        ->take(5)
        ->get();

        // Handle case where no employees have completed tasks
        if ($topEmployees->isEmpty()) {
            return response()->json(['message' => 'No employees found'], 404);
        }

        return response()->json($topEmployees);
    }
}
