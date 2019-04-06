<?php
class Avis extends ObjectModel
{
    public $id_avis;
    public $titre;
    public $contenu;

    public static $definition = array(
        'table' => 'avis',
        'primary' => 'id_avis',
        'multilang' => false,
        'fields' => array(
            'titre' => array('type' => self::TYPE_STRING, 'required' => TRUE),
            'contenu' => array('type' => self::TYPE_STRING, 'required' => TRUE),
        )
    );
}