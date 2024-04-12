<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLink extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:H:i:s Y/m/d',
        'updated_at' => 'datetime:H:i:s Y/m/d',
    ];

    protected $fillable = [
        'user_id',
        'link_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }

    public function link()
    {
        return $this->hasOne(Link::class, 'link_id');
    }
}
