<?php

/*
 * Description of FieldList
 *
 * The class represent the list of fields used in a model
 */

namespace PNM\models;

class FieldList
{

    private $expressions;
    private $names;
    /*
     * loads the list of fields in the internal arrays $names and $expressions
     */

    public function __construct(array $inputExpressions, array $inputNames = [])
    {
        $this->expressions = $inputExpressions;
        $exprIndex = 0;
        for ($i = 0; $i < count($inputExpressions); ++$i) {
            if (!empty($inputNames[$i])) {
                $this->names[$i] = $inputNames[$i]; // if the name is set, it used
            } elseif (preg_match('/\A\w+\Z/', $this->expressions[$i])) {
                $this->names[$i] = $this->expressions[$i]; //expressions consisting only of letters, numbers, and underscores are used as field names
            } else {
                $this->names[$i] = 'expr' . $exprIndex++; // expressions with non alphanumeric characters get temp names expr## if no name is set
            }
        }
    }
    /*
     * returns the part of an SQL expression with the list of fields
     */

    public function SQL()
    {
        return implode(", ", array_map(array($this, 'SQLentry'), $this->expressions, $this->names));
    }
    /*
     * used in the previous function
     */

    private function SQLentry($expression, $name)
    {
        if ($expression == $name) {
            return $expression;
        } else {
            return "($expression) AS $name";
        }
    }
    /*
     * gets the field name by index
     */

    public function getFieldName($index)
    {
        if (!empty($this->names[$index])) {
            return $this->names[$index];
        }
    }
}
