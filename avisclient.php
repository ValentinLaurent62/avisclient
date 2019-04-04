<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class AvisClient extends Module
{
    
    public function __construct()
    {
        // infos module
        $this->name = "avisclient";
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'Valentin Laurent';

        // config
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        // constructeur parent
        parent::__construct();

        // affichage
        $this->displayName = $this->l('Avis client');
        $this->description = $this->l('Permet à l\'administrateur de renseigner les avis des clients, afin qu\'un de ceux-ci soit affiché aléatoirement sur la page d\'accueil.');

        $this->confirmUninstall = $this->l('Souhaitez-vous réellemment désinstaller ce module ?');

        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('Aucun nom renseigné.');
        }
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        // enregistrer les hooks nécessaires à l'affichage
        // initialiser le nom dans la BD
        if (!parent::install() ||
            !$this->registerHook('home') ||
            !$this->registerHook('header') ||
            !Configuration::updateValue('AVISCLIENT_NAME', 'Avis client')
        ) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        // supprimer le contenu ajouté à la BD
        if (!parent::uninstall() ||
            !Configuration::deleteByName('AVISCLIENT_NAME')
            ) {
                return false;
            }
        
        return true;
    }

    // affichage avec le hook Home
    public function hookDisplayHome($params)
    {
        $this->context->smarty->assign([
            'avisclient_name' => Configuration::get('AVISCLIENT_NAME'),
            'avisclient_link' => $this->context->link->getModuleLink('avisclient', 'display')
        ]);

        return $this->display(__FILE__, 'avisclient.tpl');
    }

    // chargement du CSS et du JS
    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path.'css/avisclient.css', 'all');
    }
}