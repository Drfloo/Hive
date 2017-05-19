<?php
/**
 * Created by PhpStorm.
 * User: Florent
 * Date: 18/05/2017
 * Time: 22:45
 */
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
if($_POST['default']){
    HiveClasses::changeDefaultSupplier($_POST['id_product_attribute'],$_POST['id_supplier'],$_POST['position']);
}

Db::getInstance()->update('hive_bdd',[
    'supplier_enabled'  => $_POST['statut'],
],'`id_supplier` = '.$_POST['id_supplier'].' AND `id_product_attribute` = '.$_POST['id']);

$sql = "SELECT id_supplier,supplier_enabled,supplier_default
        FROM ps_hive_bdd 
        WHERE id_product = 6 
        AND id_product_attribute = 31";

$sql = Db::getInstance()->executeS($sql);
foreach ($sql as $row) {
    $result = array(
      'id_supplier' => $row['id_supplier'],
      'supplier_enabled' => $row['supplier_enabled'],
      'supplier_default' => $row['supplier_default']
    );
    $supplier_enabled = $result['supplier_enabled'];
    $supplier_default = $result['supplier_default'];

    if($supplier_enabled = 0 ){
        $supplier_default = 0;
        Db::getInstance()->update('table',
            array('supplier_enabled'=>'0', 'supplier_default' => 0 ));
    }
}