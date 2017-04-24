<?php
if (!defined('_PS_VERSION_'))
{
	exit;
}

class Test_Module extends Module
{
    public function __construct()
    {
        $this->name = 'test_module';
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

    // may link thinks and register configurations
    public function install()
    {
    if (Shop::isFeatureActive())
        Shop::setContext(Shop::CONTEXT_ALL);

    if (!parent::install() ||
        !$this->registerHook('leftColumn') ||
        !$this->registerHook('header') ||
        !Configuration::updateValue('MYMODULE_NAME', 'my friend')
    )
        return false;

    return true;
    }

    // this may revert all that install() method do
    public function uninstall()
    {
    if (!parent::uninstall() ||
        !Configuration::deleteByName('MYMODULE_NAME')
    )
        return false;

    return true;
    }
}
