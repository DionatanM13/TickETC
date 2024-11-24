<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventUserTicket extends Model
{
    use HasFactory;
    // Defina explicitamente o nome da tabela
    protected $table = 'event_user_ticket'; // Sem 's' no final

    // Se necessário, defina as colunas fillable
    protected $fillable = ['event_id', 'user_id', 'ticket_id'];
}
