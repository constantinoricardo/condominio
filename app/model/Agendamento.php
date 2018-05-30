<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    protected $table = 'agendamento';
    protected $fillable = ['morador_id', 'data_reserva', 'date_included_at', 'pagamento', 'descricao'];
    public $timestamps = false;

}