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
        $this->lang = false;

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

    // affichage du formulaire d'ajout d'avis
    public function renderForm()
    {
        $this->display = 'edit';
        $this->initToolbar();

        $this->fields_form = [
            'tinymce' => TRUE,
            // entête
            'legend' => [
                'title' => $this->l('Modifier avis'),
                'icon' => 'icon-cog'
            ],
            // champs
            'input' => [
                [
                    // titre de l'avis
                    'type' => 'text',
                    'label' => $this->l('Titre'),
                    'name' => 'titre',
                    'class' => 'input fixed-width-sm',
                    'size' => 50,
                    'required' => true,
                    'empty_message' => $this->l('Veuillez renseigner un titre'),
                    'hint' => $this->l('Donner un titre')
                ],
                [
                    // contenu de l'avis
                    'type' => 'textarea',
                    'label' => $this->l('Contenu'),
                    'name' => 'contenu',
                    'required' => true,
                    'lang' => false,
                    'autoload_rte' => true
                ],
            ],
            // soumission
            'submit' => [
                'title' => $this->l('Enregistrer'),
            ]
        ];

        return parent::renderForm();
    }
}