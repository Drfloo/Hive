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
    /*public static function getPriceSupplierByProduct($id_product,$id_lang){
        $product = new Product($id_product);
        $id_supplier = $product->id_supplier;
        $product_attribute = $product->getAttributesResume($id_lang);
        foreach ($product_attribute as $price_product){
            $supplier = Supplier::getProductInformationsBySupplier($id_supplier, $id_product);
            $price_products = array(
                'price_products' => $supplier
            );
        }
            return $price_products;
    }*/

    public static function addProdInstall($id_product, $id_lang)
    {
        $product = new Product($id_product);
        $id_supplier = $product->id_supplier;
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
                    'price_supplier' => '',
                    'hive' => ''
                ];
                $id_declin = $tabInfoDeclination['idDeclination'];

                $sql = "SELECT name, id_supplier, position, id_product_attribute, quantity_supplier,supplier_enabled,supplier_default
                FROM ps_hive_bdd
                NATURAL JOIN ps_supplier
                WHERE id_product_attribute =" . $id_declin . "
                ORDER BY position ASC";
                $results = Db::getInstance()->executeS($sql);
                $hive = null;
                foreach ($results as $ligne) {
                    $row = [
                        'id_supplier' => $ligne['id_supplier'],
                        'name_supplier' => $ligne['name'],
                        'position' => $ligne['position'],
                        'quantity_supplier' => $ligne['quantity_supplier'],
                        'supplier_enabled' => $ligne['supplier_enabled'],
                        'supplier_default' => $ligne['supplier_default'],
                    ];
                    $hive[] = $row;
                }
                $tabInfoDeclination['hive'] = $hive;
                $id_supplier = $row['id_supplier'];
                $data =  Db::getInstance()->executeS('SELECT product_supplier_price_te FROM ps_product_supplier
                        WHERE id_product = '.$id_product.' AND id_product_attribute = '.$id_declin.' ');
                $price_supplier = null;
                foreach ($data as $row){
                    $product_price_supplier = array(
                        'product_supplier_price_te' => $row['product_supplier_price_te']
                    );
                }
                $price_supplier[] = $product_price_supplier;
                $tabInfoDeclination['price_supplier'] = $price_supplier;

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
        $supplierData = [
            'id_supplier' => $data[0]['id_supplier'],
            'quantity' => $data[0]['quantity_supplier'],
            'position' => $data[0]['position']
        ];
        return $supplierData;
    }
    public static function dbUpdateAttributeQuantity($id_attribute,$id_supplier,$quantity){
        Db::getInstance()->update('hive_bdd',[
            'quantity_supplier'  => $quantity,
        ],'`id_supplier` = '.$id_supplier.' AND `id_product_attribute` = '.$id_attribute);
    }
    public static function changeDefaultSupplier($id_attribute,$id_supplier,$positionSupplier){
        Db::getInstance()->update('hive_bdd',[
            'supplier_default'  => 0,
        ],'`id_supplier` = '.$id_supplier.' AND `id_product_attribute` = '.$id_attribute);
        Db::getInstance()->update('hive_bdd',[
            'supplier_default'  => 1,
        ],'`position` > '.($positionSupplier).' AND `id_product_attribute` = '.$id_attribute.' AND `supplier_enabled` = 1',1);
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
    public static function getPriceSupplierByProduct($id_product,$id_product_attribute){
        $sql = 'SELECT id_supplier, product_supplier_price_te FROM ps_product_supplier WHERE id_product = '.$id_product.' AND id_product_attribute = '.$id_product_attribute.' ';
        $data = Db::getInstance()->executeS($sql);
        return $data;
    }
}