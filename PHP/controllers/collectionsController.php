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

class collectionsController
{

    public function load()
    {
        $rules = [];
        if (!empty(Request::get('title'))) {
            array_push($rules, new Rule('title', 'exactlike', Request::get('title')));
        }
        if (!empty(Request::get('full_name'))) {
            array_push($rules, new Rule(['full_name_en', 'full_name_national_language'], 'exactlike', Request::get('full_name')));
        }
        if (!empty(Request::get('location'))) {
            array_push($rules, new Rule('location', 'exactlike', Request::get('location')));
        }
        if (!empty(Request::get('tm_coll_id'))) {
            array_push($rules, new Rule('tm_coll_id', 'exact', Request::get('tm_coll_id')));
        }
        $filter = new Filter($rules); //([new Rule('title', 'not', '', 's')]);
        // $inscriptions = new inscriptions('natural_sort_format(title,7, "")', 0, 0, $filter); //$sort = null, $start = 0, $count = 0, Filter $filter = null
        $model = new collections(Request::get('sort'), (Request::get('start') ?: 0), 50, $filter); //$sort = null, $start = 0, $count = 0, Filter $filter = null
        $view = new collectionsView();
        $view->echoRender($model);
    }
}
