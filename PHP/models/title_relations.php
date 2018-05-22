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
 * 
 *
 */
class title_relations extends ListModel {

    protected function initFieldNames() {
        $this->field_names = new FieldList(['titles_id', 'title', 'predicate']);
    }

    protected function makeSQL($sort, $start, $count) {
        $sql1 = 'SELECT (object_id) as titles_id, title, predicate, title_sort, 0 as pred_sort'
                . ' FROM title_relations INNER JOIN titles ON title_relations.object_id = titles.titles_id'
                . ' WHERE subject_id=? ';
        $sql2 = 'SELECT (subject_id) as titles_id, title, CASE predicate WHEN "specificates" THEN "specificated in" '
                . 'WHEN "refers to" THEN "referred to in" ELSE predicate END  as predicate, title_sort, CASE predicate WHEN "specificates" THEN 1 '
                . 'WHEN "refers to" THEN 1 ELSE 0 END as pred_sort'
                . ' FROM title_relations INNER JOIN titles ON title_relations.subject_id = titles.titles_id'
                . ' WHERE object_id=? ';
        $sqlres = "SELECT SQL_CALC_FOUND_ROWS * FROM (($sql1) UNION ($sql2)) as unibonds ORDER BY pred_sort, predicate, title_sort";
         //echo ($sqlres);
        return $sqlres;
    }

}
