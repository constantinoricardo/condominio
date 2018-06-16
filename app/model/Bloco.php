<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Bloco extends Model
{
    protected $table = "bloco";
    protected $fillable = ["numero", "descricao"];
    public $timestamps = false;
}