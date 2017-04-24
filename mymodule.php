<?php
if (!defined('_PS_VERSION_'))
{
  exit;
}

class MyModule extends Module
{
  public function __construct()
  {
    $this->name = 'mymodule';
    $this->tab = 'front_office_features';
    $this->version = '0.0.1';
    $this->author = 'Eduardo Fernandez';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    $this->bootstrap = true;

    parent::__construct();

    $this->displayName = $this->l('My module');
    $this->description = $this->l('My module es un modulo de pruebas que no hará nada util.');

    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

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
