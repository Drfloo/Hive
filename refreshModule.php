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
/*
 * FICHIER TRES DANGEREUX !!!!!!
 *
 *
 *
Db::getInstance()->Execute('DROP TABLE `'._DB_PREFIX_.'hive_bdd`');
Db::getInstance()->Execute('
      CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'hive_bdd` (
           `id` INT(11) NOT NULL AUTO_INCREMENT,
           `id_product` INT(11) NOT NULL,
           `id_product_attribute` INT(11) NULL,
           `id_supplier` INT(11) NOT NULL,
           `position` INT(11) NOT NULL,
           `quantity_supplier` INT(11) NOT NULL,
           `supplier_default` BOOLEAN NOT NULL default 0,
           `supplier_enabled` BOOLEAN NOT NULL default 1,
           `supplier_price` INT(11) NOT NULL default 0,
           PRIMARY KEY (`id`)
           )ENGINE InnoDB DEFAULT CHARSET=utf8;');
HiveClasses::initDBRefresh($_POST['id_lang']);
header("Refresh:0");*/