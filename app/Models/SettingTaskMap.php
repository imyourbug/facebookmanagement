<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingTaskMap extends Model
{

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

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
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    public function map()
    {
        return $this->belongsTo(Map::class, 'map_id', 'id');
    }
}
