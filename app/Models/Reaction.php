<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
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
        'reaction',
        'note',
    ];

    public function reactionLinks()
    {
        return $this->hasMany(LinkReaction::class, 'reaction_id', 'id');
    }
}
