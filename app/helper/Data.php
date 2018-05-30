<?php

namespace Helper;

class Data
{

    public static function formatDateDatabase($date) : string
    {
        $date = explode("/", $date);
        $datetime = mktime(0, 0, 0, $date[1], $date[0], $date[2]);
        return date("Y-m-d", $datetime);
    }

    public static function formatPriceDatabase($price) : string
    {
        $preco = str_replace(".", "", $price);
        return str_replace(",", ".", $preco);
    }

}