<?php

/*
 * Description of info
 * A model for a information page loaded from the database
 */

namespace PNM\models;

class info
{

    public function find($id_input)
    {
        return Lookup::uniGet('select title, text from info WHERE title = ?', $id_input, 's', Lookup::RETURN_INDEXED);
    }
}
