<?php

namespace App;

use Carbon\Carbon;
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
        return $this->hasMany(Listing::class, 'landlord_id')->orderBy('updated_at', 'desc');
    }

    public function activelistings() {
        return $this->hasMany(Listing::class, 'landlord_id')
            ->whereDate('active_datetime', '<=', Carbon::now())
            ->whereDate('inactive_datetime', '>=', Carbon::now())
            ->orderBy('inactive_datetime', 'asc');
    }

    public function inactivelistings() {
        return $this->hasMany(Listing::class, 'landlord_id')
            ->whereDate('active_datetime', '>', Carbon::now())
            ->orWhereDate('inactive_datetime', '<', Carbon::now())
            ->orWhere('active_datetime', '=', null)
            ->orderBy('updated_at', 'desc');
    }
}
