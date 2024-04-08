<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskMap extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'area',
        'position',
        'target',
        'unit',
        'kpi',
        'result',
        'image',
        'detail',
        'round',
        'fake_result',
        'task_id',
        'map_id',
    ];

    public function task()
    {
        return $this->belongsTo(TaskDetail::class, 'task_id', 'id');
    }

    public function map()
    {
        return $this->belongsTo(Map::class, 'map_id', 'id');
    }
}
