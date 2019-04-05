<?php
require_once _PS_MODULE_DIR_ . '/avisclient/classes/Avis.php';

class AdminAvisController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = Avis::$definition['table'];
        $this->identifier = Avis::$definition['primary'];
        $this->className = Avis::class;
        $this->lang = true;

        parent::__construct();

        // champs de la liste
        $this->fields_list = [
            'id_avis' => [
                'title' => $this->module->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ],
            'titre' => [
                'title' => $this->module->l('titre'),
                'align' => 'left',
            ],
            'contenu' => [
                'title' => $this->module->l('contenu'),
                'align' => 'left',
            ]
        ];

        // ajout des actions
        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }
}