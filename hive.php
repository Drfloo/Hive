<?php

if (!defined('_PS_VERSION_'))
    exit;

class Hive extends Module
{
    public function __construct()
    {
        $this->name = 'Hive';
        $this->tab = 'front_office_features';
        $this->version = '0.1.0';
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

    public function hookDisplayAdminProductsExtra($params) {
        return $this->display(__FILE__, 'views/templates/admin/hive.tpl');
    }

    public function hookActionProductUpdate($params) {


    }
}