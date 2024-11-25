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
        return $this->belongsToMany(User::class, 'event_user_ticket')->withPivot('ticket_id');
    }
    public function sub_events(){
        return $this->hasMany(SubEvent::class);
    } 
    public function tickets(){
        return $this->hasMany(Ticket::class);
    } 

    public static function boot()
    {
        parent::boot();

        static::saving(function ($event) {
            if ($event->private && $event->dominio && !filter_var('test@' . $event->dominio, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception('O domínio fornecido para o evento privado é inválido.');
            }
        });
    }
}
