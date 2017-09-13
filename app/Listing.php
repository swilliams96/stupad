<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = ['title', 'landlord_id', 'rent_value', 'rent_period', 'description', 'area_id', 'lat', 'lng', 'bedrooms', 'bathrooms', 'town_distance', 'furnished', 'bills_included', 'pets_allowed' ];

    public function area() {
        return $this->belongsTo(Area::class);
    }

    public function landlord() {
        return $this->belongsTo(User::class, 'landlord_id');
    }


    // ALIASES
    public function owner() { return $this->landlord(); }
    public function user() { return $this->landlord(); }
}
