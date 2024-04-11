<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkScan extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:H:i:s Y/m/d',
    ];

    protected $fillable = [
        'time',
        'title',
        'content',
        'comment',
        'data',
        'emotion',
        'is_scan',
        'note',
        'link_or_post_id'
    ];
}
