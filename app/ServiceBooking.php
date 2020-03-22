<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Computers;
use App\User;

class ServiceBooking extends Model
{
    protected $fillable = [
        'user_id', 'service_id', 'recieve_date', 'extra_note',
    ];


    public function computer(){
        return $this->belongsTo(Computers::class,'service_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id', 'id');
    }

}
