<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndiaDistrict extends Model
{
     protected $guarded = [];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
