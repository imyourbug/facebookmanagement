<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDetail extends Model
{
    use HasFactory;

    protected $casts = [
        'plan_date' => 'datetime:Y-m-d',
        'actual_date' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d',
    ];

    protected $fillable = [
        // 'name',
        'plan_date',
        'actual_date',
        'time_in',
        'time_out',
        'range',
        'note',
        'task_id',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    public function taskChemitries()
    {
        return $this->hasMany(TaskChemistry::class, 'task_id', 'id');
    }

    public function taskMaps()
    {
        return $this->hasMany(TaskMap::class, 'task_id', 'id');
    }

    public function taskSolutions()
    {
        return $this->hasMany(TaskSolution::class, 'task_id', 'id');
    }

    public function taskItems()
    {
        return $this->hasMany(TaskItem::class, 'task_id', 'id');
    }

    public function taskStaffs()
    {
        return $this->hasMany(TaskStaff::class, 'task_id', 'id');
    }
}
