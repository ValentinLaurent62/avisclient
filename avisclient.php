<?php

// Inclusion du modèle Avis
require_once _PS_MODULE_DIR_ . '/avisclient/classes/Avis.php';

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
        // initialiser le nom dans la configuration
        if (!parent::install() ||
            !$this->registerHook('home') ||
            !$this->registerHook('header') ||
            !Configuration::updateValue('AVISCLIENT_NAME', 'Avis client')
        ) {
            return false;
        }

        return $this->_installSql() && $this->_installTab();
    }

    // installation de la BD
    protected function _installSql()
    {
        $sqlCreate = "CREATE TABLE `" . _DB_PREFIX_ . Avis::$definition['table'] . "` (
            `id_avis` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `titre` varchar(255) DEFAULT NULL,
            `contenu` TEXT,
            `date_add` datetime DEFAULT CURRENT_TIMESTAMP,
            `date_upd` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id_avis`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

        return Db::getInstance()->execute($sqlCreate);
    }

    // installation de la tab dans le backoffice
    protected function _installTab()
    {
        $tab = new Tab();
        $tabName = 'Avis client';
        // il faut définir le nom pour chaque langue
        foreach(Language::getLanguages(false) as $lang){
            $tab->name[(int) $lang['id_lang']] = $tabName;
        }
        $tab->class_name = 'AdminAvis';
        $tab->module = $this->name;
        $tab->id_parent = (int)Tab::getIdFromClassName('CONFIGURE');
        $tab->icon = 'settings_applications';

        try {
            $tab->save();
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        // supprimer la configuration
        if (!parent::uninstall() ||
            !Configuration::deleteByName('AVISCLIENT_NAME')
            ) {
                return false;
            }
        
        return $this->_uninstallSql() && $this->_uninstallTab();
    }

    // désinstallation de la BD
    protected function _uninstallSql()
    {
        $sql = "DROP TABLE ". _DB_PREFIX_ .Avis::$definition['table'];
        return Db::getInstance()->execute($sql);
    }

    // désinstallation dans le backoffice
    protected function _uninstallTab()
    {
        $idTab = (int)Tab::getIdFromClassName('AdminAvis');
        if($idTab) {
            $tab = new Tab($idTab);
            try {
                $tab->delete();
            } catch (Exception $e) {
                echo $e->getMessage();
                return false;
            }
        }
        return true;
    }

    // affichage avec le hook Home
    public function hookDisplayHome($params)
    {
        // récupérer un avis au hasard
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from(Avis::$definition['table']);
        $sql->orderBy('RAND()');
        $sql->limit(1);
        $query = Db::getInstance()->executeS($sql);

        $this->context->smarty->assign([
            'avisclient_name' => Configuration::get('AVISCLIENT_NAME'),
            'avisclient_link' => $this->context->link->getModuleLink('avisclient', 'display'),
            'mon_avis' => $query
        ]);

        return $this->display(__FILE__, 'avisclient.tpl');
    }

    // chargement du CSS et du JS
    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path.'css/avisclient.css', 'all');
    }

    // validation du formulaire de configuration
    public function getContent()
    {
        $output = null;

        // lorsqu'un formulaire est envoyé
        if(Tools::isSubmit('submit'.$this->name)) {

            // récupérer la valeur du champ AVISCLIENT_NAME
            $avisclientName = strval(Tools::getValue('AVISCLIENT_NAME'));

            // vérifier sa validité, si ok, mettre à jour la configuration
            if (
                !$avisclientName ||
                empty($avisclientName) ||
                !Validate::isGenericName($avisclientName)
            ) {
                $output .= $this->displayError($this->l('Valeur de configuration invalide'));
            } else {
                Configuration::updateValue('AVISCLIENT_NAME', $avisclientName);
                $output .= $this->displayConfirmation($this->l('Paramètres mis à jour'));
            }
        }

        return $output.$this->displayForm();
    }

    // formulaire de configuration
    public function displayForm()
    {
        // langage par défaut
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');

        // initialiser les champs
        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('Paramètres'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Valeur de configuration'),
                    'name' => 'AVISCLIENT_NAME',
                    'size' => 20,
                    'required' => true
                ]
            ],
            'submit' => [
                'title' => $this->l('Enregistrer'),
                'class' => 'btn btn-default pull-right'
            ]
        ];

        // assistant formulaire
        $helper = new HelperForm();

        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = $defaultLang;

        // titre et barre d'outils
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = [
            'save' => [
                'desc' => $this->l('Enregistrer'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules'),
            ],
            'back' => [
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Retour à la liste')
            ]
        ];

        // charger la valeur courante
        $helper->fields_value['AVISCLIENT_NAME'] = Configuration::get('AVISCLIENT_NAME');

        return $helper->generateForm($fieldsForm);
    }
}