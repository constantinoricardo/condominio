<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Apartamento extends Model
{

    protected $table = "apartamento";
    protected $fillable = ["numero", "bloco_id"];
    public $timestamps = false;

}