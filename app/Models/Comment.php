<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:H:i:s Y/m/d',
        'updated_at' => 'datetime:H:i:s Y/m/d',
    ];

    protected $fillable = [
        'title',
        'uid',
        'phone',
        'content',
        'note',
    ];

    public function commentLinks()
    {
        return $this->hasMany(LinkComment::class, 'comment_id', 'id');
    }
}
