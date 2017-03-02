<?php

class Hive extends ObjectModel{

    public static function getProductName()
    {
        $res = Db::getInstance()->getRow("
        SELECT name 
        FROM "._DB_PREFIX_."product_lang
        WHERE id_product = 1 AND id_lang = 1");

        return $res['name'];
    }
}