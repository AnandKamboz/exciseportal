<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleGroup extends Model
 {
    public function users()
   {
        return $this->belongsToMany( User::class, 'role_types', 'role_id', 'user_id' );
    }

}
