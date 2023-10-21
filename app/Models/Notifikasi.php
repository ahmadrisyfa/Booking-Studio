<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;
    protected $table = 'notifications';
    protected $guarded = ['id'];
    public function user()
    {
        return $this->belongsTo(User::class, 'to_user');
    }
    // public function toUser()
    // {
    //     return $this->belongsTo(User::class, 'to_user');
    // }
}
