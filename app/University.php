<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'short_name', 'slug', 'area_id', 'active'];

    public function area() {
        return $this->belongsTo(Area::class);
    }
}
