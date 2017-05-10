<?php

class HiveClasses extends ObjectModel{

    public static function getProductName($idProduct,$idlang)
    {
        $product = new Product($idProduct);
        $attributeProduct = $product->getDefaultIdProductAttribute();
        $supplierDef = $product->id_supplier;
        $infoDeclination = $product->getAttributesResume($idlang);
        $quantity = Product::getQuantity($idProduct,1);


        $test =$product->id_supplier;
        $att = new Attribute(1);
        /**$product->id_supplier = 1;
        $product->update();**/
       // $product_supplier = ProductSupplier::getSupplierCollection($idProduct,true);
        $listSupplier = Supplier::getLiteSuppliersList($idlang,'array');
        $attribute = Attribute::checkAttributeQty($idProduct,100);

        foreach($listSupplier as &$supplier)
        {
            $supp = new Supplier($supplier['id'], $idlang);
            $tab[] = [
                'name_supplier' => $supp->name,
                'frais_supplier' =>
                    ProductSupplier::getProductSupplierPrice($idProduct,$attributeProduct,$supp->id_supplier),
                'status_supplier' =>
                    (bool)ProductSupplier::getIdByProductAndSupplier($idProduct,$attributeProduct,$supp->id_supplier),
                'id_supplier'  => $supp->id_supplier,
            ];
        }


       $produit = [
           'nomproduit' => Product::getProductName($idProduct),
           'supplie' =>  $tab,
           'defaultsupplier' => $supplierDef,
           'stock' => $quantity,
           'infoDeclination' => $infoDeclination,
       ];
        return $produit;
    }
    public static function numberOfSupplier($id_lang){
        $listSupplier = Supplier::getLiteSuppliersList($id_lang,'array');
        return count($listSupplier);
    }
    public static function defaultQuantitySupplier($quantity,$numberSupplier){
        $quantityFloor = floor($quantity/$numberSupplier);
        $tab = array($quantityFloor + ($quantity%$numberSupplier));
        $tabResult = array_pad($tab,$numberSupplier,$quantityFloor);
        return $tabResult;
    }
    public static function addProdInstall($id_product,$id_lang){
        $product = new Product($id_product);
        $quantityProduct = Product::getQuantity($id_product);
        $listSupplier = Supplier::getLiteSuppliersList($id_lang,'array');
        $numberSuppliers = self::numberOfSupplier($id_lang);

       if(Product::getDefaultAttribute($id_product) != 0){
           $attributes = $product->getAttributesResume($id_lang);
           foreach ($attributes as $attribute){
               $quantity = $attribute['quantity'];
               $tab = self::defaultQuantitySupplier($quantity,$numberSuppliers);
               $i=0;
               foreach ($listSupplier as $supplier){
                   Db::getInstance()->insert('hive_bdd',[
                       'id_product' => $id_product,
                       "id_product_attribute" => $attribute['id_product_attribute' ],
                       'id_supplier' => $supplier['id'],
                       'position' => ($i+1),
                       'quantity_supplier'  => $tab[$i],
                   ]);
                   $i++;

               }

           }
        }
        else{
            $i=0;
            $tab = self::defaultQuantitySupplier($quantityProduct,$numberSuppliers);
            foreach ($listSupplier as $supplier) {
                Db::getInstance()->insert('hive_bdd', [
                    'id_product' => $id_product,
                    "id_product_attribute" => null,
                    'id_supplier' => $supplier['id'],
                    'position' => ($i + 1),
                    'quantity_supplier' => $tab[$i],
                ],true);
                $i++;
            }
        }
    }
    public static  function dataProductResume($id_product,$idlang){
        if(Product::getDefaultAttribute($id_product) !=0 ){
            $product = new Product($id_product);
            $infoDeclination = $product->getAttributesResume($idlang);
            foreach ($infoDeclination as &$item){
                $tabInfoDeclinaition = [
                    'idProduct' => $item["id_product"],
                    'idDeclination' => $item["id_product_attribute"],
                    'nameDeclination' => $item["attribute_designation"],
                    'hive' => ''
                ];
                $id_declin = $tabInfoDeclinaition['idDeclination'];
                $sql = "SELECT name, id_supplier, position, id_product_attribute 
                FROM ps_supplier 
                NATURAL JOIN ps_hive_bdd 
                WHERE id_product_attribute =".$id_declin."
                ORDER BY position ASC";
                $results = Db::getInstance()->ExecuteS($sql);
                $hive = null;
                foreach ($results as $ligne){
                    $row = [
                        'id_supplier' => $ligne['id_supplier'],
                        'name_supplier' => $ligne['name'],
                        'position' => $ligne['position']
                    ];
                    $hive[]= $row;
                }
                $tabInfoDeclinaition['hive'] = $hive;
                $global[]=$tabInfoDeclinaition;
            }
            return $global;
        }
    }

}
