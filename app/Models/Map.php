<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'area',
        'position',
        'target',
        'image',
        'description',
        'range',
        'active',
    ];
}
