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

    public function install(){
      $this->createDB();
        if (parent::install() == false
            OR !$this->registerHook('displayFooter')
            OR !$this->registerHook('displayAdminProductsExtra')
            OR !$this->registerHook('actionProductUpdate'))
            return false;
        return true;
    }

    function createDB(){
      Db::getInstance()->Execute('
      CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'hive_bdd` (
           `id` INT(11) NOT NULL AUTO_INCREMENT,
           `id_product` INT(11) NOT NULL,
           `id_declinaiton` INT(11) NOT NULL,
           `id_supplier` INT(11) NOT NULL,
           `position` INT(11) NOT NULL,
           `quantity_supplier` INT(11) NOT NULL,
           PRIMARY KEY (`id`)
           )ENGINE InnoDB DEFAULT CHARSET=utf8;');
    }

    public function hookDisplayAdminProductsExtra($params) {
       $id_product = $params['id_product'];
        $product = HiveClasses::getProductName($id_product,$this->context->language->id);
            $this->smarty->assign(array(
                'productname' => $product['nomproduit'],
                'supplier' => $product['supplie'],
                //'stock' => $product['stock'],
                'defsupplier' => $product['defaultsupplier'],
                'infoDeclination' => $product['infoDeclination'],
                'attribute' => $product['attribute'],
            ));
        return $this->display(__FILE__, 'views/templates/admin/hive.tpl');
    }

    public function actionAdminControllerSetMedia(){
        $this->context->controller->addCSS($this->_path.'views/css/hiveStyles.css', 'all');
        $this->context->controller->addJS($this->_path.'views/js/hiveJs.js');
    }

    public function install(){
        if (parent::install() == false
            || !$this->registerHook('displayAdminProductsExtra')
            || !$this->registerHook('actionAdminControllerSetMedia'))
            return false;
        return true;
    }

    public function uninstall()
    {
        Db::getInstance()->Execute('DROP TABLE `'._DB_PREFIX_.'hive_bdd`');
        if (!parent::uninstall())
            return false;
        return true;
    }

}
