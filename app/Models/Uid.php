<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Constant\GlobalConstant;

class Uid extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'uid',
        'phone',
    ];

    public function comment()
    {
        return $this->hasOne(Comment::class, 'uid', 'uid');
    }

    public function reaction()
    {
        return $this->hasOne(Reaction::class, 'uid', 'uid');
    }

    protected function getPhoneAttribute($value)
    {
        return substr($value, 0, GlobalConstant::LENGTH_PHONE);
    }
}
