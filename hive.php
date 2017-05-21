<?php


if (!defined('_PS_VERSION_'))
    exit;

require_once __DIR__.'/classes/HiveClasses.php';

class Hive extends Module
{
    public function __construct()
    {
        $this->name = 'Hive';
        $this->tab = 'others';
        $this->version = '1.0.0';
        $this->author = 'Damien Barber, Florent Bruziaux, Maxime Hardy';
        $this->displayName = 'Hive';
        $this->description = 'Hive est un module PrestaShop dédié au e-commerçant qui souhaite se lancer dans la livraison direct.  
                              Avec ce module, vous pouvez choisir quel est le meilleur fournisseur pour chacun des produits de votre 
                              boutique !';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
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
           `supplier_default` BOOLEAN NOT NULL default 0,
           `supplier_enabled` BOOLEAN NOT NULL default 1,
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
            OR !$this->registerHook('actionAdminLoginControllerSetMedia')
            OR !$this->registerHook('actionProductUpdate')
            OR !$this->registerHook('actionProductAdd')
            OR !$this->registerHook('actionProductAttributeUpdate')
            OR !$this->registerHook('actionProductAdd')
            OR !$this->registerHook('actionUpdateQuantity')
            OR !$this->registerHook('actionProductDelete')
            OR !$this->registerHook('actionObjectAddAfter')
            OR !$this->registerHook('actionProductAttributeDelete'))
            return false;
        return true;
    }
    public function initDB(){
        $id_lang = $this->context->language->id;
        $products = Product::getProducts($id_lang,0,999999999,'id_product','ASC');
        foreach ($products as $product){
            HiveClasses::addProdInstall((int)$product['id_product'],$id_lang);
        }
    }
    public function hookDisplayAdminProductsExtra($params) {
        $id_product = $params['id_product'];
        $id_lang = $this->context->language->id;
        $dataResume = HiveClasses::dataProductResume($id_product,$this->context->language->id);
        $dataTest = HiveClasses::dataProductResumeWithoutAttri($id_product,$id_lang);
        $product = HiveClasses::getProductName($id_product,$this->context->language->id);
            $this->smarty->assign(array(
                'productname' => $product['nomproduit'],
                'supplier' => $product['supplie'],
                'stock' => $product['stock'],
                'defsupplier' => $product['defaultsupplier'],
                'infoDeclination' => $product['infoDeclination'],
                'attribute' => $product['attribute'],
                'infoProduct' => $dataResume,
                'id_lang' => $id_lang,
            ));
        return $this->display(__FILE__, 'views/templates/admin/hive.tpl');
    }

    public function actionAdminLoginControllerSetMedia(){
        $this->context->controller->addCSS($this->_path.'views/css/hiveStyles.css', 'all');
        $this->context->controller->addJS($this->_path.'views/js/hiveJs.js');
    }


    public function uninstall()
    {
        Db::getInstance()->Execute('DROP TABLE `'._DB_PREFIX_.'hive_bdd`');
        if (!parent::uninstall())
            return false;
        return true;
    }

    protected $isSaved = false;

    public function hookActionUpdateQuantity($params){
        if($params['id_product_attribute'] != 0){
            $exist  = Db::getInstance()->getRow('SELECT * FROM `ps_hive_bdd` WHERE `id_product_attribute` = '.$params['id_product_attribute'].' ORDER BY `id` DESC ');
            if(!$exist){
                HiveClasses::dbaddAttribute($this->context->language->id,$params['id_product'],$params['id_product_attribute'],$params['quantity']);
            }
            $quantityHive = HiveClasses::dbGetAttributeTotalQuantity($params['id_product_attribute']);
            $diff = $params['quantity'] - $quantityHive;
            if ($diff != 0) {
                HiveClasses::updateHiveStock($params['id_product_attribute'],$diff);
            }
        }
    }

     public function hookActionProductDelete($params){
      $id_product = $params['id_product'];
      Db::getInstance()->Execute('
        DELETE FROM `'._DB_PREFIX_.'hive_bdd`
        WHERE `id_product`= '.$id_product.'
      ');
    }

     public function hookActionProductAttributeDelete($params){
      $id_product_attribute = $params['id_product_attribute'];
      Db::getInstance()->Execute('
        DELETE FROM `'._DB_PREFIX_.'hive_bdd`
        WHERE `id_product_attribute`= '.$id_product_attribute.'
      ');
  }

  // public function hookActionProductOutOfStock(){
    ///controllers/front/ProductController.php
  //}

  //public function hookActionBeforeCartUpdateQty(){
    ///classes/Cart.php
  //}

}
