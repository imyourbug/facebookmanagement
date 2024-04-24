<?php

namespace App\Models;

use App\Constant\GlobalConstant;
use Carbon\Carbon;
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
        'end_cursor',
        'delay'
    ];

    public function userLinks()
    {
        return $this->hasMany(UserLink::class, 'link_id', 'id');
    }

    public function commentLinks()
    {
        return $this->hasMany(LinkComment::class, 'link_id', 'id');
    }

    public function reactionLinks()
    {
        return $this->hasMany(LinkReaction::class, 'link_id', 'id');
    }

    protected function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->addHours(GlobalConstant::UTC_HOUR)->format('H:i:s Y/m/d');
    }

    protected function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->addHours(GlobalConstant::UTC_HOUR)->format('H:i:s Y/m/d');
    }
}
