<?php
/**
 * Created by PhpStorm.
 * User: Florent
 * Date: 18/05/2017
 * Time: 22:45
 */
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
Db::getInstance()->update('hive_bdd',[
    'supplier_enabled'  => $_POST['statut'],
],'`id_supplier` = '.$_POST['id_supplier'].' AND `id_product_attribute` = '.$_POST['id']);

