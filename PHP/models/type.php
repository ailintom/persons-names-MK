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

class type extends EntryModel {

    protected $tablename = 'name_types';
    protected $hasBiblio = TRUE;
    protected $idField = 'name_types_id';

    protected function initFieldNames() {

        $this->field_names = new FieldList(['name_types_id', 'parent_id', 'title', 'IF(EXISTS(SELECT * FROM name_types_temp where name_types_temp.child_id = name_types.name_types_id AND name_types_temp.parent_id = 251658604 ), "semantic", "formal")', 'note',
            'SELECT Count(DISTINCT attestations_id) FROM '
            . ' (((name_types_temp INNER JOIN names_types_xref ON name_types_temp.child_id = names_types_xref.name_types_id) INNER JOIN personal_names ON names_types_xref.personal_names_id = personal_names.personal_names_id) INNER JOIN spellings ON personal_names.personal_names_id = spellings.personal_names_id) INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id WHERE name_types_temp.parent_id=name_types.name_types_id'], ['name_types_id',
            'parent_id', 'title', 'category', 'note', 'attestations_count']);
    }

    protected function parse() {
        //This should be implemented in child classes to parse data after retrieving from the database
        $this->parseNote(['note']);
    }

    protected function loadChildren() {

        $parent = 0;
        $id = $this->get('parent_id');
        while (!empty($id)) {
            $parents[] = [$id, Lookup::get('SELECT title FROM name_types WHERE name_types_id = ?', $id)];
            $id = Lookup::get('SELECT parent_id FROM name_types WHERE name_types_id = ?', $id);
        }
        if (!empty($parents)) {
            $this->data['parents'] = $parents;
        }
    }

}
