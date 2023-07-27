<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class laporan extends Model
{
    use HasFactory;

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'id', 'grandtotal', 'time_from');
    }

    public function bookingpaket()
    {
        return $this->belongsTo(Bookingpaket::class, 'id', 'grandtotal', 'time_from');
    }
}
