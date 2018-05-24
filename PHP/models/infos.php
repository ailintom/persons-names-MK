<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PNM;

/**
 * Description of infos
 *
 * @author Tomich
 */
class infos {

    public $data;

    public function __construct() {
        $this->data = Lookup::uniGet('select title from info ORDER BY sort_order', NULL, NULL, Lookup::RETURN_INDEXED);
    }

    //put your code here
}
