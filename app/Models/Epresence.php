<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Epresence extends Model
{
    use HasFactory;

    protected $table = 'epresence';

    protected $fillable = ['id_users', 'type', 'is_approve', 'waktu'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    protected $casts = [
        'waktu' => 'datetime',
    ];
}
