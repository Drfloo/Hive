<?php

if (!defined('_PS_VERSION_'))
    exit;

var_dump(__DIR__.'/classes/Hive.php');

class Hive extends Module
{
    public function __construct()
    {
        $this->name = 'Hive';
        $this->tab = 'front_office_features';
        $this->version = '0.1.1';
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

    public function uninstall()
    {
        if (!parent::uninstall())
            return false;

        return true;
    }

    public function hookDisplayAdminProductsExtra($params) {
       // $id_product = Tools::getValue($id_product);
       // $product_name = Hive::getProductName();

            $this->smarty->assign(array(
                'productname' => "bite"
            ));

        return $this->display(__FILE__, 'views/templates/admin/hive.tpl');
    }

    public function hookActionProductUpdate($params) {

    }
}