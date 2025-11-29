<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complainant extends Model
{
    protected $guarded = [];

    public function complainantDistrict()
    {
        return $this->belongsTo(District::class, 'complainant_dist_id');
    }

    public function againstDistrict()
    {
        return $this->belongsTo(District::class, 'against_district_id');
    }

    public function detcAction()
    {
        return $this->hasOne(DetcAction::class, 'user_application_id', 'application_id');
    }
}
