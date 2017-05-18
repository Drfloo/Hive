<?php
/**
 * Created by PhpStorm.
 * User: Florent
 * Date: 03/05/2017
 * Time: 16:51
 */
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
if ($_POST['position'] == 1){
    Db::getInstance()->update('hive_bdd',[
        'supplier_default'  => 0,
    ],'`id_supplier` != '.$_POST['id_supplier'].' AND `id_product_attribute` = '.$_POST['id']);
    Db::getInstance()->update('hive_bdd',[
        'supplier_default'  => 1,
    ],'`id_supplier` = '.$_POST['id_supplier'].' AND `id_product_attribute` = '.$_POST['id']);
}
Db::getInstance()->update('hive_bdd',[
    'position'  => $_POST['position'],
],'`id_supplier` = '.$_POST['id_supplier'].' AND `id_product_attribute` = '.$_POST['id']);

