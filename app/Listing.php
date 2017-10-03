<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = ['title', 'landlord_id', 'rent_value', 'rent_period', 'description', 'short_description', 'area_id', 'lat', 'lng', 'bedrooms', 'bathrooms', 'town_distance', 'furnished', 'bills_included', 'pets_allowed', 'address1', 'address2', 'town', 'postcode', 'header_image' ];

    public function area() {
        return $this->belongsTo(Area::class);
    }

    public function landlord() {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function images() {
        return $this->hasMany(ListingImage::class)->orderBy('image_number');
    }

    public function header() {
        return $this->hasOne(ListingImage::class)->where('image_number', '=', $this->header_image);
    }


    // ALIASES
    public function owner() { return $this->landlord(); }
    public function user() { return $this->landlord(); }
}
