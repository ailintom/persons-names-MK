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
    const GET_PARAMS = ['id' => FILTER_SANITIZE_NUMBER_INT, 'ver' => FILTER_SANITIZE_NUMBER_INT, 'size' => FILTER_SANITIZE_NUMBER_INT,
        'tm_coll_id' => FILTER_SANITIZE_NUMBER_INT,
        'controller' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'name' => FILTER_SANITIZE_STRING, 'nameA' => FILTER_SANITIZE_STRING, 'nameB' => FILTER_SANITIZE_STRING,
        'title' => FILTER_SANITIZE_STRING, 'Atitle' => FILTER_SANITIZE_STRING, 'Btitle' => FILTER_SANITIZE_STRING,
        'collection' => FILTER_SANITIZE_STRING, 'inv_no' => FILTER_SANITIZE_STRING, 'material' => FILTER_SANITIZE_STRING,
        'short_name' => FILTER_SANITIZE_STRING, 'full_name' => FILTER_SANITIZE_STRING, 'location' => FILTER_SANITIZE_STRING,
        'object_type' => FILTER_SANITIZE_STRING, 'object_subtype' => FILTER_SANITIZE_STRING,
        'translation' => FILTER_SANITIZE_STRING, 'place' => FILTER_SANITIZE_STRING,
        'northof' => FILTER_SANITIZE_STRING, 'southof' => FILTER_SANITIZE_STRING, 'near' => FILTER_SANITIZE_STRING,
        'period' => FILTER_SANITIZE_STRING, 'ranke' => FILTER_SANITIZE_STRING, 'ward' => FILTER_SANITIZE_STRING,
        'hannig' => FILTER_SANITIZE_STRING, 'topbib_id' => FILTER_SANITIZE_STRING,
        'name-type-formal' => FILTER_SANITIZE_STRING, 'Aname-type-formal' => FILTER_SANITIZE_STRING, 'Bname-type-formal' => FILTER_SANITIZE_STRING,
        'name-type-semantic' => FILTER_SANITIZE_STRING, 'Aname-type-semantic' => FILTER_SANITIZE_STRING, 'Bname-type-semantic' => FILTER_SANITIZE_STRING,
        'gender' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'Agender' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'Bgender' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'length-option' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'only_persons' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'geo-filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'chrono-filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'text_content' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'script' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'relation' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'macroregion' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'match' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'match-date' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'match-region' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'start' => FILTER_SANITIZE_NUMBER_INT,
        'sort' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'find_groups_sort' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'workshops_sort' => FILTER_SANITIZE_FULL_SPECIAL_CHARS];

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

    /* The function returns the html code for the back button based on the referer of the current page
     * If JavaScript is enabled and the referer is to JavaScript the same as provided by the PHP scipt, then the browser 
     * navigates back instead of following the link so that the browser history is not filled up with back and forth moves
     * 
     */

//
    //   public static function back_button() {
//
    //       if (!empty($_SERVER['HTTP_REFERER'])) {
    //          $ref = filter_input(INPUT_SERVER, 'HTTP_REFERER', FILTER_SANITIZE_URL);
    //         $matches = [];
    //        preg_match('#.*/(.*/.*)#', $ref, $matches);
    //       $short_ref = $matches[1];
//
    //          return '<a class="main_parent-link" href="' . $ref .
    //                '" onclick="if (document.referrer===this.href){window.history.back();return false;}else{return true;}">'
    //              . 'Back to ' . $short_ref .
    //            '</a>';
    //}
    //}


    private static function init() {
        foreach (self::GET_PARAMS as $key => $filter) {

            if (array_key_exists($key, $_GET)) {

                self::$data[$key] = trim(filter_input(INPUT_GET, $key, $filter, self::default_flag($filter)));
            }
        }
        self::$data['used_ver'] = self::get('ver') ?: Config::maxVer();
        //print_r($_GET);
    }

    private static function default_flag($filter) {
        if ($filter == FILTER_SANITIZE_STRING) {
            return FILTER_FLAG_STRIP_LOW;
        }
    }

    public static function makeURL($controller, $id = NULL, $sort = NULL, $sort_field = 'sort', $useCurrentFilters = FALSE, $start = -1) {
        $request = [];
        if ($useCurrentFilters) {
            foreach (self::$data as $key => $value) {
                if (!in_array($key, ['ver', 'id', 'used_ver', 'controller'])) {
                    $request[$key] = $value;
                }
            }
        }

        if ($start > -1) {
            $request['start'] = $start;
        }

        if (!empty($sort)) {
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

        $ver_element = empty(self::$data['ver']) ? NULL : self::$data['used_ver'] . '/';
        $id_element = empty($id) ? NULL : '/' . $id;
        return BASE . $ver_element . $controller . $id_element . $requestString;
    }

}
