<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubEvent extends Model
{

    protected $dates = ['date'];
    protected $guarded = [];
    
    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }
}
