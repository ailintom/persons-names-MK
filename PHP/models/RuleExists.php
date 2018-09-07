<?php

/*
 * Description of RuleExists
 * 
 * This class represents a rule that corresponds to an EXISTS() clause in the SQL query
 */

namespace PNM\models;

class RuleExists extends Rule
{

    public function __construct($FROMWHERE, $value, $param_type = 's')
    {
        $this->WHERE = 'EXISTS(SELECT * FROM ' . $FROMWHERE . ')=1';
        $this->value = $value;
        $this->param_type = $param_type;
    }
}
