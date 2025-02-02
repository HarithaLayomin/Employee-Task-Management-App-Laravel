<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone'];  // Add any other fields

    // Define the relationship to the Task model
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
