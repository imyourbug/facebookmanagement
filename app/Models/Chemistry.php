<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chemistry extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'number_regist',
        'image',
        'description',
        'supplier',
        'active',
    ];
}
