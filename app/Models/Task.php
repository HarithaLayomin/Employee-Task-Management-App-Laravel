<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'due_date', 'status', 'employee_id'];

    // Define the relationship to the Employee model
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
