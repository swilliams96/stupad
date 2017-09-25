<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ListingImage extends Model
{
    protected $fillable = ['listing_id', 'image_number', 'file_name'];

    public function listing() {
        return $this->belongsTo(Listing::class);
    }

    public function file() {
        return asset('storage/listing_images/' .  $this->listing_id . '/' . $this->file_name);
    }


    // ALIASES
    public function url() { return $this->file(); }
    public function uri() { return $this->file(); }
}
