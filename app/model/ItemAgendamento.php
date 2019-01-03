<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class ItemAgendamento extends Model
{
    protected $table = "item_agendamento";
    protected $fillable = ["item_id", "item_agendamento"];
    public $timestamps = false;
}