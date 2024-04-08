<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'tel',
        'email',
        'tax_code',
        'avatar',
        'manager',
        'website',
        'representative',
        'field',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'customer_id', 'id');
    }
}
