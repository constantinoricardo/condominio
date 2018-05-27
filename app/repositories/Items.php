<?php

namespace Repositories;

use Model\Items as ItemsModel;

class Items
{

    public function findAll() : array
    {
        $items = ItemsModel::all("id", "descricao")->all();
        return $items;
    }

}