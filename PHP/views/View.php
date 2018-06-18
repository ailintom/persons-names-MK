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
class View
{

//put your code here
    protected $entry = null;
    protected $subEntries = null;

    public function echoRender(&$data)
    {
        //to be used in child classes
    }

    protected function descriptionElement($term, $value, $note = null, $class = null, $noteClass = null)
    {
        if (!empty($value) & !empty($note)) {
            return "\n<dt>" . $term . ":</dt>\n<dd><span" . (empty($class) ? null : ' class="' . $class . '"') . '>' . $value . '</span> <span class="' . (empty($noteClass) ? 'note' : $noteClass) . '">(' . $note . ')</span></dd>';
//<dt>Provenance:</dt><dd><span class="place"><a href="place/184549377.html">Abydos</a></span> <span class="note">(Northern Necropolis)</span></dd>
        } elseif (!empty($value)) {
            return "\n<dt>" . $term . ":</dt>\n<dd" . (empty($class) ? null : ' class="' . $class . '"') . '>' . $value . '</dd>';
        }
    }

    protected function renderURL($url, $prefix = null)
    {
        if (!empty($url)) {
            return '<a href="' . $prefix . $url . '">' . $url . '</a>';
        }
    }

    protected function addReference($title, $value, $prefix = null, $string = null)
    {
        if (empty($value)) {
            return $string;
        }
        if (!empty($string)) {
            $string .= ' / ';
        }
        $string .= $title . ': <span class="biblio-ref-no-author-date">';
        if (strpos($value, ';')) {
            $arr = explode(";", $value);
            $cumulative = null;
            foreach ($arr as $singleVal) {
                $cumulative .= (empty($cumulative) ? null : ", ") . $this->renderSingleReference(trim($singleVal), $prefix);
            }
            $string .= $cumulative;
        } else {
            $string .= $this->renderSingleReference($value, $prefix);
        }
        return $string . '</span>';
    }

    protected function renderSingleReference($value, $prefix = null)
    {
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

    public static function oldValue($field)
    {
        if (!empty(Request::get($field))) {
            return ' value = "' . Request::get($field) . '"';
        } else {
            return null;
        }
    }
    /*
     * The function returns 'checked' if the given field has the given value
     *
     */

    public static function oldValueRadio($field, $value, $default = false)
    {
        if (!empty(Request::get($field))) {
            if (Request::get($field) == $value) {
                return ' checked';
            }
        } elseif ($default) {
            return ' checked';
        }
    }

    public static function oldValueSelect($field, $value, $default = false)
    {
        if (!empty(Request::get($field))) {
            if (Request::get($field) == $value) {
                return ' selected';
            }
        } elseif ($default) {
            return ' selected';
        }
    }

    public static function genderTitle($gender)
    {
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

    public static function renderGender($gender)
    {
        if (!empty($gender)) {
            return '<span class="gender" title="' . self::genderTitle($gender) . '">' . $gender . '</span>';
        } else {
            return '&nbsp;';
        }
    }

    public static function renderObjectType($objectType)
    {
        switch ($objectType) {
            case 'Scarab, seal, scaraboid, intaglio and similar objects':
                return 'Seal/sealing';
            case 'Offering table':
                return 'Table';
            case 'Sculpture in the round':
                return 'Statue';
            case 'Unspecified':
                return '';
            case 'Written document':
                return 'Hieratic text';
            default:
                return ucfirst($objectType);
        }
    }

    public static function renderTextContent($textContent)
    {
        switch ($textContent) {
            case 'Royal name and titles':
                return 'Royal name';
            case 'Formula htp-di-nsw.t':
                return 'Formula ḤDN';
            case 'Name/filiation/title':
                return 'Name';
            case 'Letter (to the living)':
                return 'Letter';
            case 'Biographical text':
                return 'Biography';
            default:
                return ucfirst($textContent);
        }
    }

    protected function renderLat($lat)
    {
        if (!empty($lat)) {
            if (strlen(strval($lat)) == 4) {
                return substr(strval($lat), 0, 2) . "." . substr(strval($lat), 2, 2) . " ° N";
            } else {
                return $lat;
            }
        }
    }
    /*
     * Toggles filters after loading the page based on data in the request
     *
     */

    public static function toggleSingleFilter($fieldName, $filterName, $defaultVal)
    {
        if (is_array($fieldName)) {
            $res = null;
            foreach ($fieldName as $name) {
                $res .= static::toggleSingleFilter($name, $filterName, $defaultVal);
            }
            return $res;
        } else {
            if (!empty(Request::get($fieldName)) && ( empty($defaultVal) ? true : Request::get($fieldName) != $defaultVal)) {
                return "MK.toggleFilter('" . $filterName . "');";
            }
        }
    }

    protected function toggleFilters($input)
    {
        $res = null;
        foreach ((array) $input as $filter) {
            $res .= static::toggleSingleFilter($filter[0], $filter[1], isset($filter[2]) ? $filter[2] : null);
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

    protected function renderChildren($rec, $level)
    {
        $typesMV = new name_typesMicroView();
        if (empty($rec['children'])) {
            return null;
        }
        if ($level == 0) {
            echo '<ul>';
        } else {
            echo '<ul class="link-list">';
        }
        if (!empty($rec['children'])) {
            foreach ($rec['children'] as $subrec) {
                echo '<li>', $typesMV->render($subrec['title'], $subrec['name_types_id']);
                $this->renderChildren($subrec, $level + 1);
                echo '</li>';
            }
        }
        ?>
        </ul>
        <?php
    }

    protected function processBondCat($currentcat, $bondsincurrentcat, $attView)
    {
        $res = "";
        if (empty($bondsincurrentcat)) {
            return null;
        } elseif (count($bondsincurrentcat) == 1) {
            $gen = $this->genderedDesignations($currentcat, $bondsincurrentcat[0]['gender']);
            return '<li>' . ($gen ?: ObjectBonds::BOND_TYPES_SING[$currentcat] . ' ')
                    . (!empty($bondsincurrentcat[0]['wording']) ? '(<span class="wording">' . $bondsincurrentcat[0]['wording'] . '</span>)' : null) . ': '
                    . $attView->render($bondsincurrentcat[0]['title'], $bondsincurrentcat[0]['relative_id'], $bondsincurrentcat[0]['name'])
                    . '.</li>';
        } elseif (count($bondsincurrentcat) > 1) {
            $res = '<li>' . ObjectBonds::BOND_TYPES_PLUR[$currentcat] . '<ul class="children">';
            foreach ($bondsincurrentcat as $bond) {
                $res .= '<li>';
                $res .= $this->genderedDesignations($currentcat, $bond['gender']);
                $res .= (!empty($bondsincurrentcat[0]['wording']) ? '(<span class="wording">' . $bondsincurrentcat[0]['wording'] . '</span>)' : null) . ': '
                        . $attView->render($bond['title'], $bond['relative_id'], $bond['name'])
                        . '.</li>';
            }
            $res .= '</ul>';
            return $res;
        }
    }

    protected function renderBonds($bonds_data, MicroView $attView)
    {
        $currentLoc = '<ul class="bonds">';
        $currentcat = 0;
        $bondsincurrentcat = [];
        foreach ($bonds_data as $bond) {
            if ($currentcat !== $bond['predic_cat']) {
                if (!empty($currentcat)) {
                    $currentLoc .= $this->processBondCat($currentcat, $bondsincurrentcat, $attView);
                }
                $currentcat = $bond['predic_cat'];
                $bondsincurrentcat = [$bond];
            } else {
                array_push($bondsincurrentcat, $bond);
            }
        }
        $currentLoc .= $this->processBondCat($currentcat, $bondsincurrentcat, $attView);
        $currentLoc .= '</ul>';
        return $currentLoc;
    }

    protected function genderedDesignations($currentcat, $gender)
    {
        if (ObjectBonds::BOND_TYPES_PLUR[$currentcat] == 'Children') {
            switch ($gender) {
                case 'm':
                    return 'Son ';
                case 'f':
                    return 'Daughter ';
                default:
                    return 'Child ';
            }
        } elseif (ObjectBonds::BOND_TYPES_PLUR[$currentcat] == 'Siblings') {
            switch ($gender) {
                case 'm':
                    return 'Brother ';
                case 'f':
                    return 'Sister ';
                default:
                    return 'Sibling ';
            }
        } else {
            return null;
        }
    }

    protected function processAltReadings($objAltReadings)
    {
        if (!empty($objAltReadings->data)) {
            $res = ' (alternative reading' . (count($objAltReadings->data) > 1 ? 's' : null ) . ': ';
            $count = 0;
            $objView = new personal_namesMicroView();
            foreach ($objAltReadings->data as $altReading) {
                $res .= ($count++ > 0 ? ', ' : null) . $objView->render($altReading['personal_name'], $altReading['personal_names_id']);
            }
            $res .= ')';
            return $res;
        }
    }

    protected function renderPersons($persons)
    {
        $res = null;
        $personsMV = new personsMicroView();
        foreach ($persons->data as $person) {
            $res .= (empty($res) ? null : ', ') . $personsMV->render($person['title'], $person['persons_id']) . '&nbsp;(' . $person['status'] . ')';
        }
        return $res;
    }
}
