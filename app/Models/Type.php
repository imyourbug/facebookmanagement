<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Type::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Type::class, 'parent_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
