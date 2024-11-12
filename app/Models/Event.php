<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $casts = [
        'categories' => 'array'
    ];
    protected $dates = ['date'];
    protected $guarded = [];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function users(){
        return $this->belongsToMany(User::class);
    }
    public function sub_events(){
        return $this->hasMany(SubEvent::class);
    } 
    public function tickets(){
        return $this->hasMany(Ticket::class);
    } 
}
