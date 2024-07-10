<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'google2fa_secret',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'google2fa_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Determine if two-factor authentication is enabled for the user.
     *
     *
     */
    public function setGoogle2faSecreteAttribute($value)
    {
        $this->attributes['2googlefa_secret'] = encrypt(($value));
    }

    public function getGoogle2faSecreteAttribute($value)
    {
        return decrypt($value);
    }

    public function pastes()
    {
        return $this->hasMany(Paste::class);
    }
}
