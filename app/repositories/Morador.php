<?php

namespace Repositories;

use Model\Morador as ModelMorador;

class Morador
{

    public function findAll() : array
    {
        $morador = ModelMorador::all("id", "nome")->all();
        return $morador;
    }
}