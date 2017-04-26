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
        if (parent::install() == false
            OR !$this->registerHook('displayFooter')
            OR !$this->registerHook('displayAdminProductsExtra')
            OR !$this->registerHook('actionProductUpdate'))
            return false;
        return true;
    }

    function createDB(){
        Db::getInstance()->Execute('
            CREATE TABLE `prestashop`.`hive_bdd`
                ( `id` INT NOT NULL AUTO_INCREMENT ,
                 `id_product` INT NOT NULL ,
                 `id_declinaiton` INT NOT NULL ,
                 `id_supplier` INT NOT NULL ,
                 `position` INT NOT NULL ,
                 `quantity_supplier` INT NOT NULL ,
        PRIMARY KEY (`id`)) ENGINE = InnoDB
        ');
    }

    public function uninstall()
    {
        Db::getInstance()->Execute('DROP TABLE `'._DB_PREFIX_.'hive_bdd`');
        if (!parent::uninstall())
            return false;

        return true;
    }

    public function hookDisplayAdminProductsExtra($params) {
       $id_product = $params['id_product'];
        $product = HiveClasses::getProductName($id_product,$this->context->language->id);
            $this->smarty->assign(array(
                'productname' => $product['nomproduit'],
                'supplier' => $product['supplie'],
                //'stock' => $product['stock'],
                'defsupplier' => $product['defaultsupplier'],
            ));

        return $this->display(__FILE__, 'views/templates/admin/hive.tpl');
    }

    public function hookActionProductUpdate($params) {

    }
}