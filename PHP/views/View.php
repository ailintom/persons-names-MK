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
 * Description of View
 *
 * @author Tomich
 */
class View {

    //put your code here
    protected $entry = NULL;
    protected $subEntries = NULL;

    public function EchoRender(&$data) {
        echo($this->Render($data));
    }

    public function Render($data) {
        return null;
    }

    protected function descriptionElement($term, $value, $note = NULL, $class = NULL, $noteClass = NULL) {
        if (!empty($value) & !empty($note)) {
            return '<dt>' . $term . ':</dt><dd><span' . (empty($class) ? NULL : ' class="' . $class . '"') . '>' . $value . '</span> <span class="' . (empty($noteClass) ? 'note' : $noteClass) . '">(' . $note . ')</span></dd>';

            //<dt>Provenance:</dt><dd><span class="place"><a href="place/184549377.html">Abydos</a></span> <span class="note">(Northern Necropolis)</span></dd>
        } elseif (!empty($value)) {
            return '<dt>' . $term . ':</dt><dd><span' . (empty($class) ? NULL : ' class="' . $class . '"') . '>' . $value . '</span>';
        }
    }

    protected function renderURL($url, $prefix = NULL) {
        if (!empty($url)) {
            return '<a href="' . $prefix . $url . '">' . $url . '</a>';
        }
    }

    protected function addReference($title, $value, $prefix = NULL, $string = NULL) {


        if (empty($value)) {
            return $string;
        }

        if (!empty($string)) {
            $string .= ' / ';
        }
        $string .= $title . ': <span class="biblio-ref-no-author-date">';
        if (strpos($value, ';')) {
            $arr = explode(";", $value);
            $cumulative = NULL;
            foreach ($arr as $singleVal) {
                $cumulative .= (empty($cumulative) ? NULL : ", ") . $this->renderSingleReference(trim($singleVal), $prefix);
            }
            $string .= $cumulative;
        } else {
            $string .= $this->renderSingleReference($value, $prefix);
        }
        return $string . '</span>';
    }

    protected function renderSingleReference($value, $prefix = NULL) {
        if (substr($value, 0, 4) == 'http' || !empty($prefix)) {
            return $this->renderURL($value, $prefix);
        } else {
            return htmlentities($value);
        }
    }

    /*
     * The function returns the value of the given parameter from the current request formatted as a HTML value attribute for input controls
     * 
     */

    protected function oldValue($field) {

        if (!empty(Request::get($field))) {
            return ' value = "' . Request::get($field) . '"';
        } else {
            return NULL;
        }
    }

    /*
     * The function returns 'checked' if the given field has the given value
     * 
     */

    protected function oldValueRadio($field, $value, $default = FALSE) {
        if (!empty(Request::get($field))) {
            if (Request::get($field) == $value) {
                return ' checked';
            }
        } elseif ($default) {
            return ' checked';
        }
    }

    static function GenderTitle($gender) {
        /*
         * "m", "f", "?" gender unknown, or "a" for animals
         */
        switch ($gender) {
            case 'm':
                return 'male';
            case 'f':
                return 'female';
            case 'a':
                return 'animal';
            case '?':
                return 'uncertain';
        }
    }

    static function renderGender($gender) {
        if (!empty($gender)) {
            return '<span class="gender" title="' . self::GenderTitle($gender) . '">' . $gender . '</span>';
        } else {
            return '&nbsp;';
        }
    }

    /*
     * Toggles filters after loading the page based on data in the request
     * 
     */

    protected function toggleFilters($input) {
        $res = NULL;
        foreach ((array) $input as $filter) {
            if (!empty(Request::get($filter[0])) && ( empty($filter[2]) ? TRUE : Request::get($filter[0]) != $filter[2])) {
                $res .= "MK.toggleFilter('" . $filter[1] . "');";
            }
        }
        if (!empty($res)) {
            ?>            
            <script type="text/javascript">
                function toggleFiltersBasedOnRequest() {<?= $res ?>
                }
                if (window.addEventListener)
                    window.addEventListener("load",
                            toggleFiltersBasedOnRequest, false);
                else if (window.attachEvent)
                    window.attachEvent("onload",
                            toggleFiltersBasedOnRequest);
                else
                    window.onload = toggleFiltersBasedOnRequest;
            </script>
            <?php
        }
    }

}
