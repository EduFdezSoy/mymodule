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
    $this->version = '0.0.4';
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
        !Configuration::updateValue('MYMODULE_NAME', 'esta es la frase')
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

    // preparing config page
    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit'.$this->name))
        {
            $my_module_name = strval(Tools::getValue('MYMODULE_NAME'));
            if (!$my_module_name
            || empty($my_module_name)
            || !Validate::isGenericName($my_module_name))
                $output .= $this->displayError($this->l('Valor invalido'));
            else
            {
                Configuration::updateValue('MYMODULE_NAME', $my_module_name);
                $output .= $this->displayConfirmation($this->l('Configuración actualizada'));
            }
        }
        return $output.$this->displayForm();
    }

    // Config page/form
    public function displayForm()
    {
        // Get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Frase chula'),
                    'name' => 'MYMODULE_NAME',
                    'size' => 20,
                    'required' => true
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->fields_value['MYMODULE_NAME'] = Configuration::get('MYMODULE_NAME');

        return $helper->generateForm($fields_form);
    }

    // add to Left Column the mymodule.tpl
    public function hookDisplayLeftColumn($params)
    {
    $this->context->smarty->assign(
        array(
            'my_module_name' => Configuration::get('MYMODULE_NAME'),
            'my_module_link' => $this->context->link->getModuleLink('mymodule', 'display')
        )
    );
    return $this->display(__FILE__, 'mymodule.tpl');
    }

    // same as left column
    public function hookDisplayRightColumn($params)
    {
    return $this->hookDisplayLeftColumn($params);
    }

    // add to the html head the CSS
    public function hookDisplayHeader()
    {
    $this->context->controller->addCSS($this->_path.'css/mymodule.css', 'all');
    }
}
