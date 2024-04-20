<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Constant\GlobalConstant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'delay',
        'limit',
        'expire',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'date:d-m-Y',
    ];

    public function userLinks()
    {
        return $this->hasMany(UserLink::class, 'user_id', 'id');
    }

    public function userComments()
    {
        return $this->hasMany(UserComment::class, 'user_id', 'id');
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
