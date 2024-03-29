<?php
/*
 * Description of View
 * This is parent class for View pages, including all the functions used by two or more views
 */

namespace PNM\views;

use \PNM\Request;

class View {

    const titleHead = '<th>Title</th>';
    const nameHead = '<th>Name</th>';
    const epithetHead = '<th>Epithet</th>';
    const classifierHead = '<th><span title="Classifier">Class.</span></th>';
    const genderHead = '<th></th>';

    protected $entry = null;
    protected $subEntries = null;

    public function echoRender(&$data) {
        //to be used in child classes
    }

    protected function descriptionElement($term, $value, $note = null, $class = null, $noteClass = null) {
        if (!empty($value) & !empty($note)) {
            return "\n<dt>" . $term . ":</dt>\n<dd><span" . (empty($class) ? null : ' class="' . $class . '"') . '>' . $value . '</span> <span class="' . (empty($noteClass) ? 'note' : $noteClass) . '">(' . $note . ')</span></dd>';
        } elseif (!empty($value)) {
            return "\n<dt>" . $term . ":</dt>\n<dd" . (empty($class) ? null : ' class="' . $class . '"') . '>' . $value . '</dd>';
        }
    }

    protected function renderURL($url, $prefix = null) {
        if (!empty($url)) {
            return '<a href="' . $prefix . $url . '">' . $url . '</a>';
        }
    }

    /*
     * formats the bibliograhy associated with an entry
     * 
     */

    protected function renderBiblio($objbibliography) {
        $res = null;
        $bibView = new publicationsMicroView();
        foreach ($objbibliography->data as $bib_etry) {

            $res .= (empty($res) ? null : '; ');
            if (!empty($bib_etry['author_year'])) {
                $res .= $bibView->render($bib_etry['author_year'], $bib_etry['source_id']);
            } elseif (!empty($bib_etry['source_url'])) {
                $res .= "<a href='" . htmlspecialchars($bib_etry['source_url'], ENT_HTML5) . "'>" . htmlspecialchars(($bib_etry['source_title'] ?: $bib_etry['source_url']), ENT_HTML5) . "</a>" . $this->getAccessedOn($bib_etry['accessed_on']);
            } elseif (!empty($bib_etry['source_title'])) {
                $res .= htmlspecialchars($bib_etry['source_title'], ENT_HTML5) . $this->getAccessedOn($bib_etry['accessed_on']);
            }
            if (!empty($bib_etry['pages'])) {
                $res .= ", " . $bib_etry['pages'];
            }
            if (!empty($bib_etry['reference_type'])) {
                $res .= " [" . $bib_etry['reference_type'] . "]";
            }
        }
        return $res;
    }

    /*
     * Formats the "accessed on" clause
     */

    protected function getAccessedOn($accessedOn) {
        if (!empty($accessedOn)) {
            return " (accessed on " . htmlspecialchars($accessedOn, ENT_HTML5) . ")";
        }
    }

    protected function addReference($title, $value, $prefix = null, $string = null) {
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

    protected function renderSingleReference($value, $prefix = null) {
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

    public static function oldValue($field) {
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

    public static function oldValueRadio($field, $value, $default = false) {
        if (!empty(Request::get($field))) {
            if (Request::get($field) == $value) {
                return ' checked';
            }
        } elseif ($default) {
            return ' checked';
        }
    }

    public static function oldValueSelect($field, $value, $default = false) {
        if (!empty(Request::get($field))) {
            if (Request::get($field) == $value) {
                return ' selected';
            }
        } elseif ($default) {
            return ' selected';
        }
    }

    public static function genderTitle($gender) {
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

    public static function renderGender($gender) {
        if (!empty($gender)) {
            return '<span class="gender" title="' . self::genderTitle($gender) . '">' . $gender . '</span>';
        } else {
            return '&nbsp;';
        }
    }

    public static function renderObjectType($objectType) {
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

    public static function renderTextContent($textContent) {
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

    protected function renderLat($lat) {
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

    public static function toggleSingleFilter($fieldName, $filterName, $defaultVal) {
        if (is_array($fieldName)) {
            $fieldSet = false;

            foreach ($fieldName as $name) {
                if (!empty(Request::get($name))) {
                    $fieldSet = true;
                }
            }
        } else {
            $fieldSet = !empty(Request::get($fieldName)) && ( empty($defaultVal) ? true : Request::get($fieldName) != $defaultVal);
        }
        if ($fieldSet) {
            return "MK.toggleFilter('" . $filterName . "');";
        }
    }

    protected function toggleFilters($input) {
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

    protected function renderChildren($rec, $level) {
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

    protected function processBondCat($currentcat, $bondsincurrentcat, $attView) {
        $res = "";
        if (empty($bondsincurrentcat)) {
            return null;
        } elseif (count($bondsincurrentcat) == 1) {
            $gen = $this->genderedDesignations($currentcat, $bondsincurrentcat[0]['gender']);
            return '<li>' . ($gen ?: \PNM\models\bonds::BOND_TYPES_SING[$currentcat] . ' ')
                    . (!empty($bondsincurrentcat[0]['wording']) ? '(<span class="wording">' . $bondsincurrentcat[0]['wording'] . '</span>)' : null) . ': '
                    . $attView->render($bondsincurrentcat[0]['title'], $bondsincurrentcat[0]['relative_id'], $bondsincurrentcat[0]['name'])
                    . '.</li>';
        } elseif (count($bondsincurrentcat) > 1) {
            $res = '<li>' . \PNM\models\bonds::BOND_TYPES_PLUR[$currentcat] . '<ul class="children">';
            foreach ($bondsincurrentcat as $bond) {
                $res .= '<li>';
                $res .= $this->genderedDesignations($currentcat, $bond['gender']);
                $res .= (!empty($bond['wording']) ? '(<span class="wording">' . $bond['wording'] . '</span>)' : null) . ': '
                        . $attView->render($bond['title'], $bond['relative_id'], $bond['name'])
                        . '.</li>';
            }
            $res .= '</ul>';
            return $res;
        }
    }

    protected function renderBonds($bonds_data, MicroView $attView) {
        $currentLoc = '<ul class="bonds">';
        $currentcat = -1;
        $bondsincurrentcat = [];
        foreach ($bonds_data as $bond) {
            if ($currentcat !== $bond['predic_cat']) {
                if (($currentcat) > -1) {
                    $currentLoc .= $this->processBondCat($currentcat, $bondsincurrentcat, $attView);
                }
                $currentcat = $bond['predic_cat'];
                $bondsincurrentcat = [$bond];
            } else {
                array_push($bondsincurrentcat, $bond);
            }
        }
        //print_r ($bondsincurrentcat);
        $currentLoc .= $this->processBondCat($currentcat, $bondsincurrentcat, $attView);
        $currentLoc .= '</ul>';
        return $currentLoc;
    }

    protected function genderedDesignations($currentcat, $gender) {
        if (\PNM\models\bonds::BOND_TYPES_PLUR[$currentcat] == 'Children') {
            switch ($gender) {
                case 'm':
                    return 'Son ';
                case 'f':
                    return 'Daughter ';
                default:
                    return 'Child ';
            }
        } elseif (\PNM\models\bonds::BOND_TYPES_PLUR[$currentcat] == 'Siblings') {
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

    protected function processAltReadings($objAltReadings) {
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

    protected function renderPersons($persons) {
        $res = null;
        $personsMV = new personsMicroView();
        foreach ($persons->data as $person) {
            $res .= (empty($res) ? null : ', ') . $personsMV->render($person['title'], $person['persons_id']) . '&nbsp;(' . $person['status'] . ')';
        }
        return $res;
    }

    protected function encode_mdc_as_filename($vMDC) {
        if (empty($vMDC)) {
            return NULL;
        }
        $res = "";
        $byte_array = unpack('C*', $vMDC);
        foreach ($byte_array as $c) {
            $res .= sprintf("%'02s", (base_convert($c, 10, 32)));
        }
        return $res . ".png";
    }

    protected function make_mdc_url($mdc_entry) {
        if (in_array($mdc_entry, array("-", "(...)"))) {
            return $mdc_entry;
        } else {
            $url = Request::makeURL('assets/spellings', $this->encode_mdc_as_filename($mdc_entry), null, null, true, -1, true);
            return <<<EOF
<span class="spelling-attestation"><img class="spelling" src="$url" alt="$mdc_entry"></span>
EOF;
        }
    }

    protected function render_mdc($mdc) {
        // . '.png';
        if (empty($mdc)) {
            return NULL;
        }
        $mdc_arr = explode(" and ", $mdc);
        return implode(" and ", array_map(array($this, 'make_mdc_url'), $mdc_arr));
    }

    /*
     * Renders inscriptions associated with a particular object
     */

    protected function renderInscriptions($data) {
        return implode(", ", array_map(array($this, 'renderSingleInscription'), $data->data));
    }

    /*
     * Renders a single inscription; used in the previous function
     */

    protected function renderSingleInscription($Inscr) {
        $insMV = new inscriptionsMicroView();
        return $insMV->render($Inscr['title'], $Inscr['inscriptions_id']);
    }

    /*
     * Renders workshops associated with a particular object
     */

    protected function renderWorkshop($data) {
        return implode(", ", array_map(array($this, 'renderSingleWorkshop'), $data->data));
    }

    /*
     * Renders a single workshop; used in the previous function
     */

    protected function renderSingleWorkshop($Wk) {
        $wkMV = new workshopsMicroView();
        return $wkMV->render($Wk['title'], $Wk['workshops_id']) . ' (' . $Wk['status'] . (empty($Wk['note']) ? null : ', ' . $Wk['note']) . ')';
    }

    /*
     * Adds an element to an array describing the attestation
     */
    protected function pushAttetastionElement(&$attestationRender, $element, $type) {
        array_push($attestationRender, [$type, '<td>' . $element . '</td>']);
    }
    /*
     * Makes a table with attestations
     */
    protected function attestationTable($attestationRender) {

        $head = '';
        $row = '';
        foreach ($attestationRender as $el) {
            $head .= $el[0];
            $row .= $el[1];
        }
        return '<table class="name-box"><tr>' . $head . '</tr><tr>' . $row . '</tr></table>';
    }

    /*
     * Renders inventory numbers of a certain type
     */

    protected function renderInvNos($data, $isMain = false) {
        if (empty($data->data)) {
            return null;
        }
        $sortedData = $data->data;
        usort($sortedData, function ($a, $b) {
            return strnatcasecmp($a['title'], $b['title']) == 0 ? strnatcasecmp($a['inv_no'], $b['inv_no']) : strnatcasecmp($a['title'], $b['title']);
        });
        $title = null;
        $id = null;
        $invs = null;
        $res = null;
        $concat = ($isMain ? '+' : ', '); //if several main inv_nos are present, they refer to separate parts of the object listed as a+b+c; otherwise inv_nos are treated as alternative numbers of the same object
        $colView = new collectionsMicroView();
        $invView = new inv_nosMicroView();
        foreach ($sortedData as $row) {
            if ($title !== $row['title']) {
                $res .= $this->renderSingleInvNo($colView, $res, $concat, $invs, $title, $id);
                $title = $row['title'];
                $id = $row['collections_id'];
                $invs = $invView->render($row['inv_no']);
            } else {
                $invs .= (empty($invs) ? null : $concat) . $invView->render($row['inv_no']);
            }
        }
        $res .= $this->renderSingleInvNo($colView, $res, $concat, $invs, $title, $id);
        return $res;
    }

    /*
     * Renders a single inv_no; is used in the previous function
     */

    protected function renderSingleInvNo(&$colView, $res, $concat, $invs, $title, $id) {
        if (!empty($invs)) {
            return (empty($res) ? null : $concat) . $colView->render($title, $id) . ' ' . $invs;
        }
    }

    /*
     * renders the size data
     */

    protected function size($length_inp, $height, $width_inp, $thickness_inp) {
        $length = $length_inp;
        $width = $width_inp;
        if (empty($length)) {
            $length = $height;
        }
        if (empty($length) & !empty($width)) {
            $length = $width;
            $width = null;
        }

        $thickness = (empty($thickness_inp) ? null : '×' . $thickness_inp);
        if (!empty($length)) {
            return $length . (empty($width) ? null : '×' . $width . $thickness) . ' mm';
        }
    }

}
