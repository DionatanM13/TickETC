<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SubEventUser extends Pivot
{
    /**
     * Define a tabela associada.
     *
     * @var string
     */
    protected $table = 'sub_event_user';

    /**
     * Indica se o modelo deve ter timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Define os atributos que podem ser preenchidos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sub_event_id',
        'user_id',
    ];

    /**
     * Relação com o subevento.
     */
    public function subEvent()
    {
        return $this->belongsTo(SubEvent::class);
    }

    /**
     * Relação com o usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
