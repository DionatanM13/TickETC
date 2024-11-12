<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    
    protected $dates = ['date'];
    protected $guarded = [];
    
    public function event(){
        return $this->belongsTo(Event::class);
    }

}
