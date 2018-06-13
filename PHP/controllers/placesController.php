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

class placesController
{

    public function load()
    {
        $rules = [];
        if (!empty(Request::get('place'))) {
            array_push($rules, new Rule('place_name', 'exactlike', Request::get('place')));
        }
        if (!empty(Request::get('macroregion'))) {
            if (in_array(Request::get('macroregion'), ["Eastern Desert", "Nile Valley", "Western Desert", "Levant"])) {
                array_push($rules, new Rule('relative_location', 'exact', Request::get('macroregion')));
            } else {
                array_push($rules, new Rule('macro_region', 'exact', Request::get('macroregion')));
            }
        }
        if (!empty(Request::get('northof'))) {
            $northofLat = Lookup::latitude(Request::get('northof'));
            array_push($rules, new Rule('latitude', 'moreorequal', $northofLat));
        }
        if (!empty(Request::get('southof'))) {
            $southofLat = Lookup::latitude(Request::get('southof'));
            array_push($rules, new Rule('latitude', 'lessorequal', $southofLat));
        }
        if (!empty(Request::get('near'))) {
            $southofLat = Lookup::latitude(Request::get('near')) + 30;
            array_push($rules, new Rule('latitude', 'lessorequal', $southofLat));
            $northofLat = Lookup::latitude(Request::get('near')) - 30;
            array_push($rules, new Rule('latitude', 'moreorequal', $northofLat));
        }
        if (!empty(Request::get('topbib_id'))) {
            array_push($rules, new Rule('topbib_id', 'exact', Request::get('topbib_id'), 's'));
        }
        if (!empty(Request::get('tm_geoid'))) {
            array_push($rules, new Rule('tm_geoid', 'exact', Request::get('tm_geoid'), 'i'));
        }
        $filter = new Filter($rules);
        $model = new places(Request::get('sort'), (Request::get('start') ?: 0), 50, $filter);
        $view = new placesView();
        $view->echoRender($model);
    }
}
