<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{

    protected $table = "items";
    protected $fillable = ["descricao", "preco"];
    public $timestamps = false;


}