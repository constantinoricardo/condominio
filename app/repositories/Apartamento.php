<?php

namespace Repositories;

use Model\Apartamento as ModelApartamento;

class Apartamento
{

    public function alterarMoradorApartamento($apartamento_id, $morador_id)
    {

        $apartamento = new ModelApartamento();
        $object = $apartamento->find($apartamento_id);

        $object->morador_id = $morador_id;
        $object->save();
    }
}