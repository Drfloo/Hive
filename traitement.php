<?php
/**
 * Created by PhpStorm.
 * User: Florent
 * Date: 03/05/2017
 * Time: 16:51
 */
Db::getInstance()->insert('hive_bdd',[
           'id_product' => 1,
          'id_declinaiton' => 2,
          'id_supplier' => 2,
          'position' => 2,
          'quantity_supplier'  => $_POST['numberSupplierQuantity'],
       ]);