<?php
/**
 * Created by PhpStorm.
 * User: Maxime
 * Date: 19/05/2017
 * Time: 16:45
 */
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
require_once __DIR__.'/classes/HiveClasses.php';

$id_lang = $_POST['id_lang'];

HiveClasses::majProduct($id_lang);
HiveClasses::majSupplier($id_lang);

$url=$_SERVER['REQUEST_URI'];
header("Refresh: 5; URL=$url");