<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskChemistry extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'unit',
        'kpi',
        'result',
        'image',
        'detail',
        'task_id',
        'chemistry_id',
    ];

    public function task()
    {
        return $this->belongsTo(TaskDetail::class, 'task_id', 'id');
    }

    public function chemistry()
    {
        return $this->belongsTo(Chemistry::class, 'chemistry_id', 'id');
    }
}
