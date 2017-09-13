<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'student', 'landlord'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    public function listings() {
        return $this->hasMany('listings', 'landlord_id');
    }

    public function activelistings() {
        return $this->listings()
            ->whereDate('active_datetime', '<=', Carbon::now())
            ->whereDate('inactive_datetime', '>=', Carbon::now());
    }
}
