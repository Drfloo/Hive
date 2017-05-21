<?php

class HiveClasses extends ObjectModel
{

    public static function getProductName($idProduct, $idlang)
    {
        $product = new Product($idProduct);
        $attributeProduct = $product->getDefaultIdProductAttribute();
        $supplierDef = $product->id_supplier;
        $infoDeclination = $product->getAttributesResume($idlang);
        $quantity = Product::getQuantity($idProduct, 1);


        $test = $product->id_supplier;
        $att = new Attribute(1);
        /**$product->id_supplier = 1;
         * $product->update();**/
        // $product_supplier = ProductSupplier::getSupplierCollection($idProduct,true);
        $listSupplier = Supplier::getLiteSuppliersList($idlang, 'array');
        $attribute = Attribute::checkAttributeQty($idProduct, 100);

        foreach ($listSupplier as &$supplier) {
            $supp = new Supplier($supplier['id'], $idlang);
            $tab[] = [
                'name_supplier' => $supp->name,
                'frais_supplier' =>
                    ProductSupplier::getProductSupplierPrice($idProduct, $attributeProduct, $supp->id_supplier),
                'status_supplier' =>
                    (bool)ProductSupplier::getIdByProductAndSupplier($idProduct, $attributeProduct, $supp->id_supplier),
                'id_supplier' => $supp->id_supplier,
            ];
        }

        $produit = [
            'nomproduit' => Product::getProductName($idProduct),
            'supplie' => $tab,
            'defaultsupplier' => $supplierDef,
            'stock' => $quantity,
            'infoDeclination' => $infoDeclination,
        ];
        return $produit;
    }

    public static function numberOfSupplier($id_lang)
    {
        $listSupplier = Supplier::getLiteSuppliersList($id_lang, 'array');
        return count($listSupplier);
    }

    public static function defaultQuantitySupplier($quantity, $numberSupplier)
    {
        $quantityFloor = floor($quantity / $numberSupplier);
        $tab = array($quantityFloor + ($quantity % $numberSupplier));
        $tabResult = array_pad($tab, $numberSupplier, $quantityFloor);
        return $tabResult;
    }

    public static function addProdInstall($id_product, $id_lang)
    {
        $product = new Product($id_product);
        $quantityProduct = Product::getQuantity($id_product);
        $listSupplier = Supplier::getLiteSuppliersList($id_lang, 'array');
        $numberSuppliers = self::numberOfSupplier($id_lang);

        if (Product::getDefaultAttribute($id_product) != 0) {
            $attributes = $product->getAttributesResume($id_lang);
            foreach ($attributes as $attribute) {
                $quantity = $attribute['quantity'];
                $tab = self::defaultQuantitySupplier($quantity, $numberSuppliers);
                $i = 0;
                foreach ($listSupplier as $supplier) {
                    if($i == 0){
                        Db::getInstance()->insert('hive_bdd', [
                            'id_product' => $id_product,
                            'id_product_attribute' => $attribute['id_product_attribute'],
                            'id_supplier' => $supplier['id'],
                            'position' => ($i + 1),
                            'quantity_supplier' => $tab[$i],
                            'supplier_default' => 1,
                        ]);
                    }
                    else {
                        Db::getInstance()->insert('hive_bdd', [
                            'id_product' => $id_product,
                            'id_product_attribute' => $attribute['id_product_attribute'],
                            'id_supplier' => $supplier['id'],
                            'position' => ($i + 1),
                            'quantity_supplier' => $tab[$i],
                        ]);
                    }

                    $i++;
                }
            }
        } else {
            $i = 0;
            $tab = self::defaultQuantitySupplier($quantityProduct, $numberSuppliers);
            foreach ($listSupplier as $supplier) {
                Db::getInstance()->insert('hive_bdd', [
                    'id_product' => $id_product,
                    "id_product_attribute" => null,
                    'id_supplier' => $supplier['id'],
                    'position' => ($i + 1),
                    'quantity_supplier' => $tab[$i],
                ], true);
                $i++;
            }
        }
    }

    public static function dataProductResume($id_product, $idlang)
    {
        if (Product::getDefaultAttribute($id_product) != 0) {
            $product = new Product($id_product);
            $infoDeclination = $product->getAttributesResume($idlang);
            foreach ($infoDeclination as &$item) {
                $tabInfoDeclination = [
                    'idProduct' => $item["id_product"],
                    'idDeclination' => $item["id_product_attribute"],
                    'nameDeclination' => $item["attribute_designation"],
                    'hive' => ''
                ];
                $id_declin = $tabInfoDeclination['idDeclination'];
                $id_prod =  $tabInfoDeclination['idProduct'];
                $sql = "SELECT name, id_supplier, position, id_product_attribute, quantity_supplier,supplier_enabled,supplier_default
                FROM ps_hive_bdd
                NATURAL JOIN ps_supplier
                WHERE id_product_attribute =" . $id_declin . "
                ORDER BY position ASC";
                $results = Db::getInstance()->executeS($sql);
                $hive = null;
                foreach ($results as $ligne) {
                    $id_supplier = $ligne['id_supplier'];
                    $data =  Db::getInstance()->executeS('
                    SELECT product_supplier_price_te 
                    FROM ps_product_supplier
                    WHERE id_product = '.$id_prod.' 
                    AND id_product_attribute = '.$id_declin.' 
                    AND id_supplier = '.$id_supplier.' 
                    ');
                    $row = [
                        'id_supplier' => $ligne['id_supplier'],
                        'name_supplier' => $ligne['name'],
                        'position' => $ligne['position'],
                        'quantity_supplier' => $ligne['quantity_supplier'],
                        'supplier_enabled' => $ligne['supplier_enabled'],
                        'supplier_default' => $ligne['supplier_default'],
                        'price_supplier' => $data
                    ];
                    $hive[] = $row;
                }
                $tabInfoDeclination['hive'] = $hive;
                $global[] = $tabInfoDeclination;
            }
            return $global;
        }
    }
    public function updateAttribute($id_product,$idlang,$quantity){

    }
    public function compareQuantity($id_product_attribute,$quantity){
        $stock = 0;
        $i=0;
        $attributes = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'_hive_bdd` WHERE `id_product_attribute` = '.$id_product_attribute.'ORDER BY `'._DB_PREFIX_.'_hive_bdd`.`position` ASC');
        $lastSupplier = 0;
        foreach ($attributes as $attribute){
            if ($attribute['quantity_supplier'] != 0 && $attribute['supplier_enabled'] == true){

            }
            $stock =+ $attribute['quantity_supplier'];
        }
        if ($stock != $quantity){
            $majstock = $quantity - $stock;
        }
    }
    public static function getDefaultSupplier($id_attribute){
        $data = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'hive_bdd` WHERE `id_product_attribute` = '.$id_attribute.' AND `supplier_default` = 1 ');
           if($data){
               $supplierData = [
                   'id_supplier' => $data[0]['id_supplier'],
                   'quantity' => $data[0]['quantity_supplier'],
                   'position' => $data[0]['position']
               ];
               return $supplierData;
           }

    }
    public static function dbGetAttributeTotalQuantity($id_attribute){
        $data = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'hive_bdd` WHERE `id_product_attribute` = '.$id_attribute.' ');
        $quantity = 0;
        foreach ($data as $item){
            $quantity = $quantity + $item['quantity_supplier'];
        }
        return $quantity;
    }
    public static function dbUpdateAttributeQuantity($id_attribute,$id_supplier,$quantity){
        Db::getInstance()->update('hive_bdd',[
            'quantity_supplier'  => $quantity,
        ],'`id_supplier` = '.$id_supplier.' AND `id_product_attribute` = '.$id_attribute);

    }
    public static function dbSwitchDefaultSupplier($id_attribute,$new_supplier,$old_supplier){
        Db::getInstance()->update('hive_bdd',[
            'supplier_default'  => 1,
        ],'`id_supplier` = '.$new_supplier.' AND `id_product_attribute` = '.$id_attribute);
        Db::getInstance()->update('hive_bdd',[
            'supplier_default'  => 0,
        ],'`id_supplier` = '.$old_supplier.' AND `id_product_attribute` = '.$id_attribute);

    }
    public static function changeDefaultSupplier($id_attribute,$id_supplier,$positionSupplier){
        Db::getInstance()->update('hive_bdd',[
            'supplier_default'  => 0,
        ],'`id_supplier` = '.$id_supplier.' AND `id_product_attribute` = '.$id_attribute);
        Db::getInstance()->update('hive_bdd',[
            'supplier_default'  => 1,
        ],'`position` > '.($positionSupplier).' AND `id_product_attribute` = '.$id_attribute.' AND `supplier_enabled` = 1 ORDER BY `position` ASC',1);
    }
    public static function dbSuppliersActive($id_attribute,$id_supplier){
        $data = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'hive_bdd` WHERE `id_product_attribute` = '.$id_attribute.' AND `id_supplier` = '.$id_supplier.'
        ');
        return (bool)($data[0]['supplier_default']);
    }
    public static function updateHiveStock($id_attribute,$diff){
            $defaultSupplier = self::getDefaultSupplier($id_attribute);
           if($diff > 0 || ($diff < 0 && abs($diff) < $defaultSupplier['quantity'])){
               $newStock = $diff + $defaultSupplier['quantity'];
               self::dbUpdateAttributeQuantity($id_attribute,$defaultSupplier['id_supplier'],$newStock);
               return true;
           }
            $diff = $diff + $defaultSupplier['quantity'];
            self::dbUpdateAttributeQuantity($id_attribute, $defaultSupplier['id_supplier'], 0);
            self::changeDefaultSupplier($id_attribute, $defaultSupplier['id_supplier'], $defaultSupplier['position']);
            self::updateHiveStock($id_attribute, $diff);
    }
    public static function addProductBDD($id_product,$id_lang){
        $i = 0;
        $quantityProduct = Product::getQuantity($id_product);
        $listSupplier = Supplier::getLiteSuppliersList($id_lang, 'array');
        $numberSuppliers = self::numberOfSupplier($id_lang);

        $tab = self::defaultQuantitySupplier($quantityProduct, $numberSuppliers);
            foreach ($listSupplier as $supplier) {
                Db::getInstance()->insert('hive_bdd', [
                    'id_product' => $id_product,
                    "id_product_attribute" => $id_product,
                    'id_supplier' => $supplier['id'],
                    'position' => ($i + 1),
                    'quantity_supplier' => $tab[$i],
                ], true);
                $i++;
            }
            return true;
    }
    public static function addProdProductAttributeBDD($id_product,$id_lang){

        if (Product::getDefaultAttribute($id_product) != 0) {
            $attributes = $product->getAttributesResume($id_lang);
            foreach ($attributes as $attribute) {
                $quantity = $attribute['quantity'];
                $tab = self::defaultQuantitySupplier($quantity, $numberSuppliers);
                $i = 0;
                foreach ($listSupplier as $supplier) {
                    Db::getInstance()->insert('hive_bdd', [
                        'id_product' => $id_product,
                        'id_product_attribute' => $attribute['id_product_attribute'],
                        'id_supplier' => $supplier['id'],
                        'position' => ($i + 1),
                        'quantity_supplier' => $tab[$i],
                    ]);
                    $i++;
                }
            }
        }
    }

    public static function initDBRefresh($id_lang){
        $products = Product::getProducts($id_lang,0,10000,'id_product','ASC');
        foreach ($products as $product){
            HiveClasses::addProdInstall((int)$product['id_product'],$id_lang);
        }
    }
    public static function majProduct($id_lang){
        $numberProduct = Db::getInstance()->getValue('SELECT COUNT(DISTINCT `id_product`) FROM `ps_product`');
        $numberProductHive = Db::getInstance()->getValue('SELECT COUNT(DISTINCT `id_product`) FROM `ps_hive_bdd` ');
        if($numberProduct > $numberProductHive){
            $limit = ($numberProduct - $numberProductHive);
            $newProducts = Db::getInstance()->executeS('SELECT `id_product` FROM `ps_product` ORDER BY `ps_product`.`id_product` DESC LIMIT '.$limit.' ');
            foreach ($newProducts as $product){
                HiveClasses::addProdInstall((int)$product['id_product'],$id_lang);
            }
        }
    }
    public static function majSupplier($id_lang){
        $listSupplier = Db::getInstance()->executeS('SELECT * FROM `ps_supplier` ORDER BY `ps_supplier`.`id_supplier` ASC ');
        $numberSupplier = self::numberOfSupplier($id_lang);
        $listProducts = Product::getProducts($id_lang,0,999999999999,'id_product','ASC');
        $numberRow = Db::getInstance()->getValue('SELECT COUNT(DISTINCT `id_supplier`) FROM `ps_hive_bdd` ');
        if($numberSupplier > (int)$numberRow){

            $listIdHive = Db::getInstance()->executeS('SELECT `id_product`,`id_product_attribute` FROM `ps_hive_bdd` GROUP BY `id_product`,`id_product_attribute` ');
            $i=$numberRow;
            while ($i != $numberSupplier){
                $supplier = $listSupplier[$i];

                self::dbaddNewSupplier($supplier,$listIdHive,$i + 1);
                $i++;
            }

        }
    }
    public static function dbaddNewSupplier($supplier,$listProduct,$number){
        foreach ($listProduct as $product){
            Db::getInstance()->insert('hive_bdd', [
                'id_product' => $product['id_product'],
                "id_product_attribute" => $product['id_product_attribute'],
                'id_supplier' => $supplier['id_supplier'],
                'position' => $number,
                'quantity_supplier' => 0,
            ], true);;
        }

    }
    public static function dbaddAttribute($id_lang,$id_product,$id_product_attribute,$quantity){
        self::majSupplier($id_lang);
        $listSupplier = Supplier::getLiteSuppliersList($id_lang, 'array');
        $numberSuppliers = self::numberOfSupplier($id_lang);
        $tab = self::defaultQuantitySupplier($quantity, $numberSuppliers);
        $i = 0;
        foreach ($listSupplier as $supplier) {
            if($i == 0){
                Db::getInstance()->insert('hive_bdd', [
                    'id_product' => $id_product,
                    'id_product_attribute' => $id_product_attribute,
                    'id_supplier' => $supplier['id'],
                    'position' => ($i + 1),
                    'quantity_supplier' => $tab[$i],
                    'supplier_default' => 1,
                ]);
            }
            else {
                Db::getInstance()->insert('hive_bdd', [
                    'id_product' => $id_product,
                    'id_product_attribute' => $id_product_attribute,
                    'id_supplier' => $supplier['id'],
                    'position' => ($i + 1),
                    'quantity_supplier' => $tab[$i],
                ]);
            }

            $i++;
        }
    }

}