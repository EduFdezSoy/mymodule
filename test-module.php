<?php
if (!defined('_PS_VERSION_'))
{
	exit;
}

class MyModule extends Module
{
    public function __construct()
    {
        $this->name = 'test-module';
        $thhis->tab = 'front_office_features';
        $this->version = '0.0.1';
        $this->author = 'Eduardo Fernandez';
        $this->need_isntance = 0;
        $this->ps_versions_compilacy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->bootstrap = true;
        
        parent::construct();
        
        $this->displayName = $this->l('Test Module');
        $this->description = $this->l('Descripcion tope chula del modulo.');
        
        $this->confirmUninstall = $this->l('Estás seguro?');
        
        if (!Configuration::get('MYMODULE_NAME'))
            $this->warning = $this->l('No name provided');
    }
    
    public function install()
    {   
        if (!parent::install())
            return false;
        return true;
    }
}
