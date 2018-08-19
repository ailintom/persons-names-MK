<?php

/*
 * Description of Rule
 *
 * This class represents a query rule set by the user corresponing to a single WHERE clause in the SQL query
 */

namespace PNM\models;

class Rule
{

    public $WHERE = null; // The resulting clause to be added to the WHERE string used to prepare the statement
    public $value = []; // The value to be passed to mysqli_stmt::bind_param
    public $param_type = null; // The type of the parameter to be passed to mysqli_stmt::bind_param
    protected $field = null; // the array with fields used to make a rule
    protected $compare = null;

    public function __construct($field, $compareString, $value, $param_type = 's')
    {
        $this->field = (array) $field;
        switch ($compareString) {
            case 'exact':
                $this->compare = "=";
                $rendVal = $value;
                break;
            case 'not':
                $this->compare = "<>";
                $rendVal = $value;
                break;
            case 'exactlike':
                $this->compare = "LIKE";
                $rendVal = str_replace("*", "%", $value);
                break;
            case 'inexact':
                $this->compare = "LIKE";
                $rendVal = '%' . str_replace("*", "%", $value) . '%';
                break;
            case 'startswith':
                $this->compare = "LIKE";
                $rendVal = $value . '%';
                break;
            case 'endswith':
                $this->compare = "LIKE";
                $rendVal = '%' . $value;
                break;
            case 'not-later':
            case 'lessorequal':
                $this->compare = "<=";
                $rendVal = $value;
                break;
            case 'not-earlier':
            case 'moreorequal':
                $this->compare = ">=";
                $rendVal = $value;
                break;
        }
        $total = count($this->field);
        //
        $this->WHERE = null;
        for ($i = 0; $i < $total; $i++) {
            if (is_array($rendVal)) {
                if ($this->compare == "=") {
                    $arrcomp = ' IN ';
                } elseif ($this->compare == "=") {
                    $arrcomp = ' NOT IN ';
                }
                $this->WHERE .= $this->field[$i] . $arrcomp . '(' . implode(array_map(function ($val) {
                                    return '?';
                                }, $rendVal), ', ') . ') ';
                $this->param_type .= str_repeat($param_type, count($rendVal));
                $this->value = array_merge($this->value, $rendVal);
            } else {
                $this->WHERE .= $this->field[$i] . ' ' . $this->compare . ' ? ';
                $this->param_type .= $param_type;
                array_push($this->value, $rendVal);
            }
            $this->WHERE .= ($i < ($total - 1) ? ' OR ' : null);
        }
    }
}
