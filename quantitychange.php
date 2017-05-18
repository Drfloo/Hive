<?php
/**
 * Created by PhpStorm.
 * User: Florent
 * Date: 19/05/2017
 * Time: 00:15
 */
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
require_once __DIR__.'/classes/HiveClasses.php';
HiveClasses::dbUpdateAttributeQuantity($_POST['id'],$_POST['id_supplier'],$_POST['quantity']);
$quantity = HiveClasses::dbGetAttributeTotalQuantity($_POST['id']);
StockAvailable::setQuantity($_POST['id_product'],$_POST['id'],$quantity);
var_dump($_POST);