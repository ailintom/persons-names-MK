<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PNM\controllers;

/**
 * Description of infoController
 *
 *
 */
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
