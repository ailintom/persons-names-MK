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

namespace PNM\models;

/**
 *
 *
 */
class ObjectBibliography extends ListModel
{

    protected $tablename = 'biblio_refs LEFT JOIN publications ON biblio_refs.source_id = publications.publications_id';
    public $defaultsort = 'order_value, year DESC, source_url, source_title, author_year_sort';

    //(source_id>0) DESC , source_url, source_title, author_year_sort
    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['author_year', 'publications_id', 'source_id', 'source_url', 'source_title', 'pages', 'DATE_FORMAT(accessed_on, "%M %e, %Y")', 'reference_type'], ['author_year', 'publications_id', 'source_id', 'source_url', 'source_title', 'pages', 'accessed_on', 'reference_type']);
    }
}
