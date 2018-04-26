<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = ['id'];

    public function from() {
        return $this->belongsTo(User::class, 'from');
    }

    public function to() {
        return $this->belongsTo(User::class, 'to');
    }


    // ALIASES
    public function sender() { return $this->from(); }
    public function receiver() { return $this->to(); }
}
