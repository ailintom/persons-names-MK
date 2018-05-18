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
 * Description of Bibliography
 *
 */
class titles extends ListModelTitleSort {

    protected $tablename = 'titles';
    public $defaultsort = 'title';

    protected function initFieldNames() {
        $this->field_names = new FieldList(['titles_id', 'title', 'CASE 2*EXISTS (SELECT gender FROM titles_att INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id WHERE titles_att.titles_id = titles.titles_id AND gender="f") + EXISTS (SELECT gender FROM titles_att INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id WHERE titles_att.titles_id = titles.titles_id AND gender="m") WHEN 3 THEN "both" WHEN 2 THEN "f" WHEN 1 THEN "m" END', 'SELECT Count(attestations_id) FROM titles_att WHERE titles_att.titles_id=titles.titles_id', 'usage_period', 'usage_area', 'ward_fischer', 'hannig', 'translation_en'],
                ['titles_id', 'title', 'gender', 'count_attestations', 'usage_period', 'usage_area', 'ward_fischer', 'hannig', 'translation_en']);
    }
   

  
}
