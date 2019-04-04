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
        // vérification de l'installation
        return parent::install();
    }

    public function uninstall()
    {
        // lors de la désinstallation
        return parent::uninstall();
    }
}