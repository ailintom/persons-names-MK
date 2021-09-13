<?php

/*
 * Description of inscriptionsMicroView
 * This is a MicroView used to render links to inscriptions
 */

namespace PNM\views;
use \PNM\Request;
use \PNM\models\Lookup;

class objectsMicroView extends MicroView {

    protected $controller = "inscription";

    protected function makeURL($inputid) {
        $inscription_id = Lookup::get("SELECT inscriptions_id from objects_inscriptions_xref where objects_id = ?", $inputid, 'i');
        return Request::makeURL($this->controller, $inscription_id);
    }

}
