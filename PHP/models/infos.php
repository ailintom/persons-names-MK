<?php

/*
 * Description of infos
 * A model representing database records with information pages   
 */

namespace PNM\models;

class infos
{

    public $data;

    public function __construct()
    {
        $this->data = Lookup::uniGet('select title from info ORDER BY sort_order', null, null, Lookup::RETURN_INDEXED);
    }
}
