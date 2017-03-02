<?php

class HiveClasses extends ObjectModel{

    public static function getProductName()
    {
        $res = Product::getProductName(1);


        return $res;
    }
}