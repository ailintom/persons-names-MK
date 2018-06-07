<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PNM;

/**
 * Description of infoController
 *
 *  
 */
class infoController {

    public function load() {
        $id = Request::get('id');
        if (!isset($id)) {
            $infos = New infos();
            (new startView)->echoRender($infos->data);
        } elseif ($id == 'impressum') {
            (new infoView)->echoRender(['Impressum', Config::IMPRESSUM]);
        } elseif ($id == 'privacy') {
            (new infoView)->echoRender(['Privacy Policy', Config::PRIVACY]);
        } else {
            $this->record = new info; // an instance of the EntryModel class
            //$this->record->find(Request::get('id'));
            (new infoView)->echoRender($this->record->find($id)[0]);
        }
    }

}
