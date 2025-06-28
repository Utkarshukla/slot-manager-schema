<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
        public function users()
    {
        return $this->belongsToMany(User::class, 'user_plan_relation')
                    ->withPivot(['start_date', 'end_date'])
                    ->withTimestamps();
    }
}
