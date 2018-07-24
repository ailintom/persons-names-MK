<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PNM\models;

/**
 * Description of RuleExists
 *
 * @author Tomich
 */
class RuleExists extends Rule
{

    public $WHERE = null;
    public $value = [];
    public $param_type = null;

    public function __construct($FROMWHERE, $value, $param_type = 's')
    {
        $this->WHERE = 'EXISTS(SELECT inscriptions_id FROM ' . $FROMWHERE . ')=1';
        $this->value = $value;
        $this->param_type = $param_type;
    }
}
