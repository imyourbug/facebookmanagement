<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkComment extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:H:i:s Y/m/d',
        'updated_at' => 'datetime:H:i:s Y/m/d',
    ];

    protected $fillable = [
        'comment_id',
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
}
