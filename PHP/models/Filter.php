<?php

/*
 * Description of Filter
 *
 * Represents a set of rules used to perform an SQL query. 
 * Is a wraparound for Mysqli bindParam
 */

namespace PNM\models;

class Filter
{

    public $WHERE = null;
    protected $rules;
    /*
     * loads the set of rules
     */

    public function __construct(array $data = [])
    {
        $this->rules = $data;
        foreach ($this->rules as $rule) {
            $this->WHERE .= (empty($this->WHERE) ? null : ' AND ') . '(' . $rule->WHERE . ')';
        }
    }
    /*
     * is a wraparound for Mysqli bindParam
     * $stmt - mysqli statement
     * $double_params indicates that a double set of params is used
     */

    public function bindParam($stmt, $double_params = false)
    {
        if (empty($this->rules)) {
            return null;
        }
        $type = null;
        $params = [];
        foreach ($this->rules as $rule) {
            $type .= $rule->param_type;
            $params = array_merge($params, (array) $rule->value);
        }
        if ($double_params) {
            foreach ($this->rules as $rule) {
                $type .= $rule->param_type;
                $params = array_merge($params, (array) $rule->value);
            }
        }
        $stmt->bind_param($type, ...$params);
    }
    /*
     * returns the set of rules as an array
     */

    public function getRules()
    {
        return $this->rules;
    }
}
