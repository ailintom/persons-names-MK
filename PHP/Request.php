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
 * Description of Request
 *
 * @author Tomich
 */
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
        'hannig' => FILTER_SANITIZE_STRING, 'topbib_id' => FILTER_SANITIZE_STRING,
        'form_type' => FILTER_SANITIZE_STRING, 'Aform_type' => FILTER_SANITIZE_STRING, 'Bform_type' => FILTER_SANITIZE_STRING,
        'sem_type' => FILTER_SANITIZE_STRING, 'Asem_type' => FILTER_SANITIZE_STRING, 'Bsem_type' => FILTER_SANITIZE_STRING,
        'gender' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'Agender' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'Bgender' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'size-option' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'only_persons' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'geo-filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'chrono-filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'text_content' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'script' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'relation' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'macroregion' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'match' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'match-date' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'match-region' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'start' => FILTER_SANITIZE_NUMBER_INT,
        'sort' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'find_groups_sort' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'workshops_sort' => FILTER_SANITIZE_FULL_SPECIAL_CHARS];
    const DEFAULTS = ['size-option' => 'greater', 'geo-filter' => 'all', 'chrono-filter' => 'during',
        'gender' => 'any', 'Agender' => 'any', 'Bgender' => 'any', 'match-date' => 'attested',
        'match-region' => 'attested', 'match' => 'inexact'];

    private static $data = [];

    private function __construct() {
        
    }

    private function __clone() {
        
    }

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

    private static function init() {
        foreach (self::GET_PARAMS as $key => $filter) {

            if (array_key_exists($key, $_GET)) {
                self::$data[$key] = trim(filter_input(INPUT_GET, $key, $filter, self::default_flag($filter)));
            }
        }
        
        self::$data['used_ver'] = isset(self::$data['ver']) ? self::get('ver')  : Config::maxVer();
    }

    private static function default_flag($filter) {
        if ($filter == FILTER_SANITIZE_STRING) {
            return FILTER_FLAG_STRIP_LOW;
        }
    }

    /*
     * checks if the value of the given field is empty or equals the default value for this field
     */

    public static function emptyOrDefault($field, $value) {
        if (!isset($value) or !isset($value[0]) ) {
            return TRUE;
        }
        if (array_key_exists($field, self::DEFAULTS)) {
            if (self::DEFAULTS[$field] == $value) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /*
     * returns a stable url for the current request
     */

    public static function stableURL() {
        return self::makeURL(self::get('controller'), self::get('id'), NULL, NULL, TRUE, -1, TRUE);
    }

    /*
     * returns a stable url for a different version of the current request
     */

    public static function changeVer($ver) {

        return self::makeURL(self::get('controller'), self::get('id'), NULL, NULL, TRUE, -1, FALSE, $ver);
    }

    public static function makeURL($controller, $id = NULL, $sort = NULL, $sort_field = 'sort', $useCurrentFilters = FALSE, $start = -1, $forceVer = FALSE, $ver = NULL) {
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
                $requestString .= array_keys($request)[$i] . '=' . urlencode(array_values($request)[$i]) . ($i < count($request) - 1 ? '&' : NULL);
            }
        } else {
            $requestString = NULL;
        }
        if (isset($ver)) {
            $ver_element = ($ver == Config::maxVer()) ? NULL : $ver . '/';
        } else {
            $ver_element = (!$forceVer && !isset(self::$data['ver'])) ? NULL : self::get('used_ver') . '/';
        }
        if (isset($id) && !in_array($controller, ['info', 'assets/spellings'])) {
// short ids are used for all controllers except spelling images (which use long ids) and information pages (which use textual ids)

            $idArr = (array) $id;
            $short = implode('#', array_map('PNM\\ID::shorten', array_filter($idArr)));
            $id_element = '/' . $short;
        } elseif (isset($id)) {
            $id_element = '/' . $id;
        } else {
            $id_element = NULL;
        }


        return Config::BASE . $ver_element . $controller . $id_element . $requestString;
    }

}
