<?php

class HiveClasses extends ObjectModel{


    public static function getProductName($idProduct,$idlang)
    {
        $product = new Product($idProduct);
        $attributeProduct = $product->getDefaultIdProductAttribute();
        $supplierDef = $product->id_supplier;
        $stock = $product->getAttributeCombinations($idlang,false);
        $prod1 = Product::getQuantity($idProduct,1);
        /**$product->id_supplier = 1;
        $product->update();**/

        $product_supplier = ProductSupplier::getSupplierCollection($idProduct,true);
        $bite = Supplier::getLiteSuppliersList($idlang,'array');

        foreach($bite as &$supplier)
        {

            $supp = new Supplier($supplier['id'], $idlang);


            $tab[] = ['name_supplier' => $supp->name,
                      'frais_supplier' => ProductSupplier::getProductSupplierPrice($idProduct,$attributeProduct,$supp->id_supplier),
                      'status_supplier' => (bool)ProductSupplier::getIdByProductAndSupplier($idProduct,$attributeProduct,$supp->id_supplier),
                      'id_supplier'  => $supp->id_supplier,


            ];

        }

       $produit = [
           'nomproduit' => Product::getProductName($idProduct),
           'supplie' =>  $tab,
           'defaultsupplier' => $supplierDef,
           'stock' => $prod1,
       ];


        return $produit;
    }

}