<?php

namespace Repositories;

use Illuminate\Database\Capsule\Manager as DB;
use Model\Items as ItemsModel;

class Items
{

    public function findAll() : array
    {
        $items = ItemsModel::all("id", "descricao")->all();
        return $items;
    }

    public static function findPrice($item_id) : string
    {
        $preco = "";

        $items = DB::table("items")
            ->select("preco")
            ->whereIn("id", $item_id)
            ->get();

        foreach ($items as $item) {
            $preco += $item->preco;
        }

        return $preco;

    }

}