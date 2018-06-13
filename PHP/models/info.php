<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PNM;

/**
 * Description of info
 *
 * @author Tomich
 */
class info
{

    //put your code here
    public function find($id_input)
    {
        return Lookup::uniGet('select title, text from info WHERE title = ?', $id_input, 's', Lookup::RETURN_INDEXED);
    }
}
