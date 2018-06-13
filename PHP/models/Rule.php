<?php

/*
 * MIT License
 *
 * Copyright (c) 2017 Alexander Ilin-Tomich (unless specified otherwise for individual source files and documents)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace PNM;

/**
 * Description of Rule
 *
 * @author Tomich
 */
class Rule
{

    //put your code here
    public $WHERE = null;
    public $value = [];
    public $param_type = null;
    protected $field = null;
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
