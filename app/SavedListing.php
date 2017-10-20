<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SavedListing extends Model
{
    public $timestamps = false;

    protected $fillable = ['id', 'user', 'listing', 'saved_datetime', 'unsaved_datetime'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function listing() {
        return $this->belongsTo(Listing::class);
    }
}
