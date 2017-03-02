<?php

class HiveClasses extends ObjectModel{

    public static function getProductName($idProduct,$idlang)
    {
        $attributeProduct = Product::getProductAttributesIds($idProduct);
        $product_supplier = ProductSupplier::getSupplierCollection($idProduct,true);
        $bite = $product_supplier->getResults();
        foreach($bite as $key=>&$supplier)
        {

            $supp = new Supplier($supplier->id_supplier, $idlang);
            $tab[] = ['name_supplier' => $supp->name,
                      'frais_supplier' => ProductSupplier::getProductSupplierPrice($idProduct,$attributeProduct,$supp->id_supplier),
                      'status_supplier' => $supp->active];


        }
        /*$bite = $bite->getResults();
        $bite = $bite[1];
        $bite = $bite->id_supplier;*/
       $produit = [
           'nomproduit' => Product::getProductName($idProduct),
           'supplie' =>  $tab,];

        return $produit;
    }
}