<?php

class HiveClasses extends ObjectModel{

    public static function getProductName($idProduct,$idlang)
    {
        $product = new Product($idProduct);
        $attributeProduct = $product->getDefaultIdProductAttribute();
        $supplierDef = $product->id_supplier;
        $infoDeclination = $product->getAttributesResume($idlang);
        $quantity = Product::getQuantity($idProduct,1);

        /*Db::getInstance()->insert('hive_bdd',[
            'id_product' => 1,
           'id_declinaiton' => 2,
           'id_supplier' => 2,
           'position' => 2,
           'quantity_supplier'  => 2,
        ]);*/
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

        foreach ($infoDeclination as &$item){
            $tabInfoDeclinaition[] = [
                'idDeclination' => $item["id_product_attribute"],
                'nameDeclination' => $item["attribute_designation"],
                'defaultQuantityDeclination' => $item["quantity"],
            ];
        }

       $produit = [
           'nomproduit' => Product::getProductName($idProduct),
           'supplie' =>  $tab,
           'defaultsupplier' => $supplierDef,
           'stock' => $quantity,
           'infoDeclination' => $infoDeclination,
           'attribute' => $tabInfoDeclinaition,
           'test' => $test,
       ];
        return $produit;
    }
    public static function addProd($id_product){
        $product = new Product($id_product);
        if(Product::getDefaultAttribute($id_product) != 0){

           // foreach ()
        };
    }


}
