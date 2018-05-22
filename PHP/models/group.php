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

class group extends EntryModel {

    protected $tablename = 'find_groups';
    protected $hasBiblio = TRUE;
    protected $idField = 'find_groups_id';

    protected function initFieldNames() {

        $this->field_names = new FieldList(['find_groups_id', 'site', 'site_area', 'exact_location', 'title', 'find_group_type', 'architecture', 'human_remains',
            'finds', 'disturbance', 'dating', 'dating_note', 'note']);
    }

     protected function parse() {
        $this->parseNote(['dating_note', 'note']);
    }
    
        protected function loadChildren() {
        $filter = new Filter([New Rule('find_groups_id', 'exact', $this->getID(), 'i')]);
        $objIns = New inscriptions(NULL, 0, 0, $filter);
        $this->data['inscriptions'] = $objIns;
    }

}
