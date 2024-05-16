<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLink extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
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
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function link()
    {
        return $this->belongsTo(Link::class, 'link_id', 'id');
    }

    
}
