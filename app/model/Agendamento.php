<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{

    protected $table = 'agendamento';
    protected $fillable = ['data', 'morador_id', 'motivo'];
}