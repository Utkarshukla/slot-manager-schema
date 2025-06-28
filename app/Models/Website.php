<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $guarded=[];
    protected $casts =[
        'content'=>'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
