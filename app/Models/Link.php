<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:H:i:s Y/m/d',
        'updated_at' => 'datetime:H:i:s Y/m/d',
    ];

    protected $fillable = [
        'time',
        'title',
        'content',
        'comment_first',
        'comment_second',
        'data_first',
        'data_second',
        'emotion_first',
        'emotion_second',
        'is_scan',
        'note',
        'link_or_post_id',
        'type',
    ];

    public function userLinks()
    {
        return $this->hasMany(UserLink::class, 'link_id');
    }
}
