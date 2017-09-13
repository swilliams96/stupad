<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'suffix', 'active'];

    public function universities() {
        return $this->hasMany(University::class);
    }

    public function listings() {
        return $this->hasMany(Listing::class);
    }

    public function activelistings() {
        return $this->listings()
            ->whereDate('active_datetime', '<=', Carbon::now())
            ->whereDate('inactive_datetime', '>=', Carbon::now());
    }

}
