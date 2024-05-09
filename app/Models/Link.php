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
        'is_scan',
        'note',
        'link_or_post_id',
        'type',
        'end_cursor',
        'delay',
        'status',
        //
        'comment',
        'diff_comment',
        'data',
        'diff_data',
        'reaction',
        'diff_reaction',
    ];

    public function userLinks()
    {
        return $this->hasMany(UserLink::class, 'link_id', 'id');
    }

    public function commentLinks()
    {
        return $this->hasMany(LinkComment::class, 'link_id', 'id')->orderByDesc('created_at');
    }

    public function reactionLinks()
    {
        return $this->hasMany(LinkReaction::class, 'link_id', 'id');
    }
}
