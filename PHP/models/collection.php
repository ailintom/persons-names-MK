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

class collection extends EntryModel
{

    protected $tablename = 'collections';
    protected $hasBiblio = false;
    protected $idField = 'collections_id';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['collections_id', 'title', 'full_name_en', 'full_name_national_language', 'location', 'url',
            'online_collection', 'tm_coll_id', 'SELECT COUNT(DISTINCT inscriptions_id) FROM inv_nos '
            . 'WHERE inv_nos.collections_id = collections.collections_id and status<>"erroneous"', 'thot_concept_id',
            'artefacts_url'], ['collections_id', 'title', 'full_name_en', 'full_name_national_language', 'location', 'url',
            'online_collection', 'tm_coll_id', 'inscriptions_count', 'thot_concept_id',
            'artefacts_url']);
    }

    protected function loadChildren()
    {
        $filter = new Filter([new Rule('collections_id', 'exact', $this->getID(), 'i')]);
        $objIns = new inv_nos(null, 0, 0, $filter);
        $this->data['inv_nos'] = $objIns;
    }
}
