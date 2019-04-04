<?php
class avisclientdisplayModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        $this->setTemplate('module:avisclient/views/templates/front/display.tpl');
    }
}