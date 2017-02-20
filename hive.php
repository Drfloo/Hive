<?php

class Hive extends Module
{
    public function __construct()
    {
        $this->name = 'hive';
        $this->tab = 'dashboard';
        $this->version = '0.1.0';
        $this->author = 'Damien Barber, Florent Bruziaux, Maxime Hardy';
        $this->displayName = 'Hive';
        $this->description = 'Module à la con pour';
        $this->bootstrap = true;

        parent::__construct();
    }

    public function getContent(){
       return $this->display(__FILE__, 'getContent.tpl');
    }
}