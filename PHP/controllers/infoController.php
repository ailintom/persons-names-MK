<?php

/*
 * Description of infoController
 * This controller is used to load a single information page; Impressum and Privacy Policy hardcoded in the PHP, other pages are loaded from the database
 *
 */

namespace PNM\controllers;

use \PNM\Config,
    \PNM\Request;

class infoController {

    protected $record; // the variable holding the data

    public function load() {
        $id = Request::get('id');
        if (empty($id)) {
            $infos = new \PNM\models\infos();
            (new \PNM\views\startView())->echoRender($infos->data);
        } elseif ($id == 'impressum') {
            (new \PNM\views\infoView())->echoRender(['Impressum', Config::IMPRESSUM]);
        } elseif ($id == 'privacy') {
            (new \PNM\views\infoView())->echoRender(['Privacy Policy', Config::PRIVACY]);
        } else {
            $this->record = new \PNM\models\info(); // an instance of the EntryModel class
            if (!isset($this->record->find($id)[0])) { // the record was not found
                (new \PNM\views\NotFoundView())->echoRender();
            } else {
                (new \PNM\views\infoView())->echoRender($this->record->find($id)[0]);
            }
        }
    }
}
