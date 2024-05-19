<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLink extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'is_on_at' => 'datetime:H:i:s Y/m/d',
        'created_at' => 'datetime:H:i:s Y/m/d',
        'updated_at' => 'datetime:H:i:s Y/m/d',
    ];

    protected $fillable = [
        'user_id',
        'link_id',
        'is_scan',
        'title',
        'note',
        'type',
        'is_on_at',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function link()
    {
        return $this->belongsTo(Link::class, 'link_id', 'id');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s Y/m/d');
    }
}
