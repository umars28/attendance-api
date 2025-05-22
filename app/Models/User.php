<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['nama', 'email', 'npp', 'npp_supervisor'];

    protected $hidden = ['password'];

    public function epresences()
    {
        return $this->hasMany(Epresence::class, 'id_users');
    }

    public function subordinates()
    {
        return $this->hasMany(User::class, 'npp_supervisor', 'npp');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'npp_supervisor', 'npp');
}
