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

class publication extends EntryModel {

    protected $tablename = 'publications';
    protected $hasBiblio = FALSE;
    protected $idField = 'publications_id';
    public $tables = [['criteria'], ['inscriptions', 'Inscribed objects'], ['find_groups', 'Find groups'], ['workshops'], ['persons'], ['titles'], ['spellings'], ['personal_names', 'Personal names'], ['name_types', 'Name types']];

    protected function initFieldNames() {

        $this->field_names = new FieldList(['publications_id', 'author_year', 'html_entry', 'oeb_id']);
    }

    protected function loadChildren() {

        foreach ($this->tables as $table) {
            /*
             *     public $defaultsort = 'pages_sort';

              protected function initFieldNames() {
              $this->field_names = new FieldList( ['biblio_refs_id', 'reference_type', 'object_id', 'pages', 'note']);
             */
            $SQL = 'SELECT biblio_refs.biblio_refs_id as biblio_refs_id, biblio_refs.reference_type as reference_type, '
                    . 'biblio_refs.object_id as object_id, biblio_refs.pages as pages, biblio_refs.note as note, '
                    . $table[0] . '.' . Note::TITLE_FIELDS[$table[0]] . ' as title FROM biblio_refs INNER JOIN ' . $table[0]
                    . ' ON biblio_refs.object_id = ' . $table[0] . '.' . $table[0] . '_id WHERE biblio_refs.source_id = ? '
                    . 'ORDER BY pages_sort';

            $this->data[$table[0]] = Lookup::getList($SQL, $this->getID(), 'i');
        }
    }

}
