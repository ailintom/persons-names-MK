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
 * Description of FieldList
 *
 * @author Tomich
 */
class FieldList {

    private $expressions;
    private $names;

    public function __construct(array $inputExpressions, array $inputNames = []) {
        $this->expressions = $inputExpressions;
        $exprIndex = 0;
        for ($i = 0; $i < count($inputExpressions); ++$i) {
            if (empty($inputNames[$i])) {
                if (preg_match('/\A\w+\Z/', $this->expressions[$i])) {
                    $this->names[$i] = $this->expressions[$i];
                } else {
                    $this->names[$i] = 'expr' . $exprIndex++;
                }
            } else {
                $this->names[$i] = $inputNames[$i];
            }
        }
    }

    public function SQL() {
        return implode(", ", array_map(array($this, 'SQLentry'), $this->expressions, $this->names));
    }

    private function SQLentry($expression, $name) {
        if ($expression == $name) {
            return $expression;
        } else {
            return "($expression) AS $name";
        }
    }

    public function getFieldName($index) {
        if (!empty($this->names[$index])) {
            return $this->names[$index];
        }
    }

}
