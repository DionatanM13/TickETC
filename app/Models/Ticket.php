<?php

namespace App\Models;

use GuzzleHttp\Psr7\Query;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    
    protected $dates = ['date'];
    protected $guarded = [];

    protected $fillable = ['title', 'batch', 'price', 'quantity', 'description'];

    public function scopeAvaiableLowestBatch($query){
        $lowestBatch = $query->where('quantity', '>', 0)
                        ->orderBy('batch', 'asc')
                        ->min('batch');

        // Retorna todos os ingressos com o menor lote
        return $query->where('batch', $lowestBatch)
                     ->where('quantity', '>', 0)
                     ->get();
    }
    
    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }

}
