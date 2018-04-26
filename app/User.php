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

    public function savedlistings() {
        return $this->hasMany(SavedListing::class, 'user')
            ->whereNull('unsaved_datetime')
            ->orderBy('saved_datetime', 'desc');
    }

    public function unsavedlistings() {
        return $this->hasMany(SavedListing::class, 'user')
            ->whereNotNull('unsaved_datetime')
            ->orderBy('unsaved_datetime', 'desc');
    }


    public function sentmessages($to = null) {
        return is_null($to)
            ? $this->hasMany(Message::class, 'from')
            : $this->hasMany(Message::class, 'from')->where('to', $to);
    }

    public function receivedmessages($from = null) {
        return is_null($from)
            ? $this->hasMany(Message::class, 'to')
            : $this->hasMany(Message::class, 'to')->where('from', $from);
    }

    public function messages($other = null) {
        $sent = $this->sentmessages($other)->get();
        $received = $this->receivedmessages($other)->get();
        $messages = $sent->merge($received)->sortBy('sent_at');
        $messages->map(function ($message) {
            $message['other'] = ($message['from'] == $this->id
                ? $message['to']
                : $message['from']);

            $user = User::find($message['other']);
            is_null($user)
                ? $message['full_name'] = 'unknown'
                : $message['full_name'] = $user->first_name . ' ' . $user->last_name;

            // TODO: convert the message string into HTML to allow line breaks (similar to listing descriptions)
            // TODO: will also need to truncate this into a new "summary" field on each message (using the ListingController summarise function)
        });
        return $messages;
    }

}
