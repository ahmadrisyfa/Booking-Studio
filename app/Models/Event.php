<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $table = 'event';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function services(){
        return $this->belongsTo(Services::class);
    }

    public function getStatusAttribute($input){
        return [
            0 => 'Menunggu konfirmasi',
            1 => 'Booked',
            2 => 'Sukses',
            3 => 'Batal'
        ][$input];
    }
}
