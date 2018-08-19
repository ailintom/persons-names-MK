<?php

/*
 * Description of infoController
 * This controller is used to load a single information page; Impressum and Privacy Policy hardcoded in the PHP, other pages are loaded from the database
 *
 */

namespace PNM\controllers;

class infoController
{

    public function load()
    {
        $id = \PNM\Request::get('id');
        if (!isset($id)) {
            $infos = new \PNM\models\infos();
            (new \PNM\views\startView())->echoRender($infos->data);
        } elseif ($id == 'impressum') {
            (new \PNM\views\infoView())->echoRender(['Impressum', \PNM\Config::IMPRESSUM]);
        } elseif ($id == 'privacy') {
            (new \PNM\views\infoView())->echoRender(['Privacy Policy', \PNM\Config::PRIVACY]);
        } else {
            $this->record = new \PNM\models\info(); // an instance of the EntryModel class
            //$this->record->find(\PNM\Request::get('id'));
            (new \PNM\views\infoView())->echoRender($this->record->find($id)[0]);
        }
    }
}
