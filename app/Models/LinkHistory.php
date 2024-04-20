<?php

namespace App\Models;

use App\Constant\GlobalConstant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkHistory extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:H:i:s Y/m/d',
        'updated_at' => 'datetime:H:i:s Y/m/d',
    ];

    protected $fillable = [
        'comment_first',
        'comment_second',
        'data_first',
        'data_second',
        'emotion_first',
        'emotion_second',
        'link_id',
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id', 'id');
    }

    public function link()
    {
        return $this->belongsTo(Link::class, 'link_id', 'id');
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
