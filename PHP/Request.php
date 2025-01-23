<?php

/*
 * Description of Request
 * This class is used to sanitize and handle the user request and to generate site-internal hypertext links
 */

namespace PNM;

class Request {

// This constant stores the filter parameteters for each possible value in GET requests
    const GET_PARAMS = ['id' => FILTER_SANITIZE_STRING, 'ver' => FILTER_SANITIZE_NUMBER_INT, 'size' => FILTER_SANITIZE_NUMBER_INT,
        'tm_coll_id' => FILTER_SANITIZE_NUMBER_INT, 'tm_geoid' => FILTER_SANITIZE_NUMBER_INT,
        'controller' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'name' => FILTER_SANITIZE_STRING, 'Aname' => FILTER_SANITIZE_STRING, 'Bname' => FILTER_SANITIZE_STRING,
        'title' => FILTER_SANITIZE_STRING, 'Atitle' => FILTER_SANITIZE_STRING, 'Btitle' => FILTER_SANITIZE_STRING,
        'collection' => FILTER_SANITIZE_STRING, 'inv_no' => FILTER_SANITIZE_STRING, 'material' => FILTER_SANITIZE_STRING,
        'short_name' => FILTER_SANITIZE_STRING, 'full_name' => FILTER_SANITIZE_STRING, 'location' => FILTER_SANITIZE_STRING,
        'object_type' => FILTER_SANITIZE_STRING, 'object_subtype' => FILTER_SANITIZE_STRING,
        'translation' => FILTER_SANITIZE_STRING, 'place' => FILTER_SANITIZE_STRING,
        'northof' => FILTER_SANITIZE_STRING, 'southof' => FILTER_SANITIZE_STRING, 'near' => FILTER_SANITIZE_STRING,
        'period' => FILTER_SANITIZE_STRING, 'ranke' => FILTER_SANITIZE_STRING, 'ward' => FILTER_SANITIZE_STRING,
        'hannig' => FILTER_SANITIZE_STRING, 'taylor' => FILTER_SANITIZE_STRING,
        'ayedi' => FILTER_SANITIZE_STRING, 'topbib_id' => FILTER_SANITIZE_STRING,
        'form_type' => FILTER_SANITIZE_STRING, 'Aform_type' => FILTER_SANITIZE_STRING, 'Bform_type' => FILTER_SANITIZE_STRING,
        'sem_type' => FILTER_SANITIZE_STRING, 'Asem_type' => FILTER_SANITIZE_STRING, 'Bsem_type' => FILTER_SANITIZE_STRING,
        'gender' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'Agender' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'Bgender' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'size-option' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'only_persons' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'geo-filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'chrono-filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'text_content' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'script' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'relation' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'macroregion' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'match' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'match-date' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'match-region' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'start' => FILTER_SANITIZE_NUMBER_INT, 'tla' => FILTER_SANITIZE_NUMBER_INT, 'pnmid' => FILTER_SANITIZE_NUMBER_INT,
        'sort' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'find_groups_sort' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'workshops_sort' => FILTER_SANITIZE_FULL_SPECIAL_CHARS];
    const DEFAULTS = ['size-option' => 'greater', 'geo-filter' => 'all', 'chrono-filter' => 'during',
        'gender' => 'any', 'Agender' => 'any', 'Bgender' => 'any', 'match-date' => 'attested',
        'match-region' => 'attested', 'match' => 'inexact'];
    const CONTROLLERS = ['bibliography', 'collection', 'collections', 'criterion', 'group',
        'info', 'inscription', 'inscriptions', 'name', 'names', 'people', 'person', 'place', 'places',
        'publication', 'title', 'titles', 'type', 'types', 'workshop', 'thesaurus', 'object', 'id'];

    private static $data = [];
    public static $noDatalist;

    public static function get($key, $default = null) {
        if (empty(self::$data)) {
            self::init();
        }
        return self::has($key) ? self::$data[$key] : $default;
    }

    public static function has($key) {
        if (empty(self::$data)) {
            self::init();
        }
        return array_key_exists($key, self::$data);
    }

// returns true if value is valid
    private static function custom_validity_check($key, $value) {
        if ($key === 'sort' or $key === 'find_groups_sort' or $key === 'workshops_sort') {
            if (!((0 === substr_compare($value, " DESC", -5)) or (0 === substr_compare($value, " ASC", -4)))) {
                return false;
            } elseif (preg_match('/[^a-zA-Z_]/', trim(substr($value, 0, -4)))) {
                return false;
            }
        }elseif (self::GET_PARAMS [$key] == FILTER_SANITIZE_NUMBER_INT){
return filter_var ($value, FILTER_VALIDATE_INT);
}

        return true;
    }

    private static function init() {
        foreach (self::GET_PARAMS as $key => $filter) {
            if (array_key_exists($key, $_GET)) {
                $keyval = trim(filter_input(INPUT_GET, $key, $filter, self::defaultFlag($filter)));

                if ($keyval !== "" &&  self::custom_validity_check($key, $keyval)) {
                    self::$data[$key] = $keyval;
                }
            }
        }
        if (isset(self::$data['ver']) && !in_array(self::$data['ver'], array_column(Config::VERSIONS, 0))) {
            self::$data['ver'] = null;
        }
        self::$data['used_ver'] = isset(self::$data['ver']) ? self::get('ver') : self::maxVer();
        if (!in_array(self::get('controller'), self::CONTROLLERS)) { // only valid controller names can be used
            self::$data['controller'] = 'info';
            self::$data['id'] = null;
        }
        //$isSafari
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = trim(filter_input(INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW));
            if (preg_match("/^((?!chrome|android).)*safari/i", $userAgent) || preg_match("/.*MSIE [23456789]\..*/i", $userAgent)) {
                self::$noDatalist = true;
            }
        }
    }

    private static function defaultFlag($filter) {
        if ($filter == FILTER_SANITIZE_STRING) {
            return FILTER_FLAG_STRIP_LOW;
        }
    }

    /*
     * checks if the value of the given field is empty or equals the default value for this field
     */

    public static function emptyOrDefault($field, $value) {
        if (!isset($value) or !isset($value[0])) {
            return true;
        }
        if (array_key_exists($field, self::DEFAULTS)) {
            if (self::DEFAULTS[$field] == $value) {
                return true;
            }
        }
        return false;
    }

    /*
     * returns the maximum version of all the versions defined in Config.php
     */

    public static function maxVer() {
        return Config::VERSIONS[count(Config::VERSIONS) - 1][0];
    }

    /*
     * returns the date of the given database version in the MySQL date literal format
     */

    public static function verDate($version) {
        $date = array_column(Config::VERSIONS, 1)[array_search($version, array_column(Config::VERSIONS, 0))];
        $myDate = substr($date, 6) . "-" . substr($date, 3, 2) . "-" . substr($date, 0, 2);
        return $myDate;
    }

    /*
     * returns a stable url for the current request
     */

    public static function stableURL() {
        if (strpos($_SERVER['REQUEST_URI'], 'ontology')) {
            return self::makeURL('ontology', null, null, null, false, -1, false, null);
        } else {
            return self::makeURL(self::get('controller'), self::get('id'), null, null, true, -1, true);
        }
    }

    /*
     * returns a stable url for a different version of the current request
     */

    public static function changeVer($ver) {
        return self::makeURL(self::get('controller'), self::get('id'), null, null, true, -1, false, $ver);
    }

    public static function makeURL($controller, $id = null, $sort = null, $sort_field = 'sort', $useCurrentFilters = false, $start = -1, $forceVer = false, $ver = null) {
        $request = [];
        if ($useCurrentFilters) {
            foreach (self::$data as $key => $value) {
                if (!in_array($key, ['ver', 'id', 'used_ver', 'controller']) && !self::emptyOrDefault($key, $value)) {
                    $request[$key] = $value;
                }
            }
        }
        if ($start > -1) {
            $request['start'] = $start;
        }
        if (isset($sort)) {
            $request[$sort_field] = $sort;
        }
        if (!empty($request)) {
            $requestString = "?";
            for ($i = 0; $i < count($request); ++$i) {
                $requestString .= array_keys($request)[$i] . '=' . urlencode(array_values($request)[$i]) . ($i < count($request) - 1 ? '&' : null);
            }
        } else {
            $requestString = null;
        }
        if (isset($ver)) {
            $ver_element = ($ver == self::maxVer()) ? null : $ver . '/';
        } else {
            $ver_element = (!$forceVer && !isset(self::$data['ver'])) ? null : self::get('used_ver') . '/';
        }

        if (isset($id) && !in_array($controller, ['info', 'assets/spellings'])) {
// short ids are used for all controllers except spelling images (which use long ids) and information pages (which use textual ids)
            $idArr = (array) $id;
            if (isset($idArr['id']) and isset($idArr['inscriptions_id']) and !empty($idArr['id']) and empty($idArr['inscriptions_id'])) {
                $idArr = (array) $idArr['id'];
            }

            $short = implode('#', array_map('PNM\\ID::shorten', $idArr));
            $id_element = '/' . str_replace(' ', '+', $short);
        } elseif (isset($id)) {
            $id_element = '/' . str_replace(' ', '+', $id);
        } else {
            $id_element = null;
        }

        return Config::BASE . $ver_element . $controller . $id_element . $requestString;
    }

}
