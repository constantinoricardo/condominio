<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Morador extends Model
{

    protected $table = "morador";
    protected $fillable = ["nome", "cpf", "apartamento_id"];
    public $timestamps = false;
}