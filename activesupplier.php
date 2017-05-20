<?php
/**
 * Created by PhpStorm.
 * User: Florent
 * Date: 18/05/2017
 * Time: 22:45
 */
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
require_once __DIR__.'/classes/HiveClasses.php';

$supplier = Db::getInstance()->getRow('
            SELECT * 
            FROM `'._DB_PREFIX_.'hive_bdd` 
            WHERE `id_product_attribute` = '.$_POST['id'].'
            AND `id_supplier` = '.$_POST['id_supplier'].' ');

if($_POST['statut'] == 0){
    if($supplier['supplier_default'] == 1){
        HiveClasses::changeDefaultSupplier($_POST['id'],$_POST['id_supplier'],$_POST['position']);
    }

    HiveClasses::dbUpdateAttributeQuantity($_POST['id'],$_POST['id_supplier'],0);
    $quantity = HiveClasses::dbGetAttributeTotalQuantity($_POST['id']);
    StockAvailable::setQuantity($_POST['id_product'],$_POST['id'],$quantity);

}

if($_POST['statut'] == 1){
    if($supplier['supplier_default'] == 0){
        $data = Db::getInstance()->getRow('
                SELECT * 
                FROM `'._DB_PREFIX_.'hive_bdd` 
                WHERE `id_product_attribute` = '.$_POST['id'].' 
                AND `id_supplier` != '.$_POST['id_supplier'].' 
                AND `supplier_default` = 1 
                AND `position` > '.$_POST['position'].' ');

        if($data){
            HiveClasses::dbSwitchDefaultSupplier($_POST['id'],$_POST['id_supplier'],$data['id_supplier']);
        }
    }
}

Db::getInstance()->update('hive_bdd',[
    'supplier_enabled'  => $_POST['statut'],
],'`id_supplier` = '.$_POST['id_supplier'].' AND `id_product_attribute` = '.$_POST['id']);
