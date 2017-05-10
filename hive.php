<?php

if (!defined('_PS_VERSION_'))
    exit;

require_once __DIR__.'/classes/HiveClasses.php';

class Hive extends Module
{
    public function __construct()
    {
        $this->name = 'Hive';
        $this->tab = 'front_office_features';
        $this->version = '0.5.1';
        $this->author = 'Damien Barber, Florent Bruziaux, Maxime Hardy';
        $this->displayName = 'Hive';
        $this->description = 'Description du module [A FAIRE]';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->confirmUninstall = $this->l('Êtes-vous sûr de vouloir supprimer le module ?');
    }

    public function getContent(){
       return $this->display(__FILE__, 'getContent.tpl');
    }


    function createDB(){
      Db::getInstance()->Execute('
      CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'hive_bdd` (
           `id` INT(11) NOT NULL AUTO_INCREMENT,
           `id_product` INT(11) NOT NULL,
           `id_product_attribute` INT(11) NULL,
           `id_supplier` INT(11) NOT NULL,
           `position` INT(11) NOT NULL,
           `quantity_supplier` INT(11) NOT NULL,
           PRIMARY KEY (`id`)
           )ENGINE InnoDB DEFAULT CHARSET=utf8;');
    }
    public function install(){
        $this->createDB();
        $this->initDB();
        if (parent::install() == false
            OR !$this->registerHook('displayFooter')
            OR !$this->registerHook('actionProductUpdate')
            OR !$this->registerHook('actionProductSave')
            OR !$this->registerHook('displayAdminProductsExtra')
            OR !$this->registerHook('actionAdminControllerSetMedia')
            OR !$this->registerHook('actionProductUpdate')
            OR !$this->registerHook('actionProductAttributeUpdate'))
            return false;
        return true;
    }
    public function initDB(){
        $id_lang = $this->context->language->id;
        $products = Product::getProducts($id_lang,0,10000,'id_product','ASC');
        foreach ($products as $product){
            HiveClasses::addProdInstall((int)$product['id_product'],$id_lang);
        }
    }
    public function hookDisplayAdminProductsExtra($params) {
       $id_product = $params['id_product'];
        $product = HiveClasses::getProductName($id_product,$this->context->language->id);
            $this->smarty->assign(array(
                'productname' => $product['nomproduit'],
                'supplier' => $product['supplie'],
                'stock' => $product['stock'],
                'defsupplier' => $product['defaultsupplier'],
                'infoDeclination' => $product['infoDeclination'],
                'attribute' => $product['attribute'],
                'test' => "",
            ));
        $sql = "SELECT name, id_supplier, position FROM ps_supplier NATURAL JOIN ps_hive_bdd ORDER BY position ASC";
        if ($results = Db::getInstance()->ExecuteS($sql))
            $this->smarty->assign( 'position', $results);
        return $this->display(__FILE__, 'views/templates/admin/hive.tpl');
    }

    //public function actionAdminControllerSetMedia(){
      //  $this->context->controller->addCSS($this->_path.'views/css/hiveStyles.css', 'all');
      //  $this->context->controller->addJS($this->_path.'views/js/hiveJs.js');
  //  }


    public function uninstall()
    {
        Db::getInstance()->Execute('DROP TABLE `'._DB_PREFIX_.'hive_bdd`');
        if (!parent::uninstall())
            return false;
        return true;
    }

    protected $isSaved = false;

    public function hookActionProductUpdate($params){
        if ($this->isSaved)
            return null;
        //HiveClasses::addProdInstall(1, $this->context->language->id);

        $data = Tools::getAllValues();
        $isInsert = Db::getInstance()->insert('hive_bdd',[
                "id_product" => $data['idProduct34'],
                "id_product_attribute" => $data['idDeclination34'],
                "id_supplier" => $data['idSupplier34'],
                "position" => $data["compteur34"],
                "quantity_supplier" => $data["numberSupplierQuantity341"],
            ]);

        if ($isInsert)
            $this->isSaved = true;

        return true;
    }
}
