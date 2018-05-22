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

class person extends EntryModel {

    protected $tablename = 'persons';
    protected $hasBiblio = TRUE;
    protected $idField = 'persons_id';

    protected function initFieldNames() {

        $this->field_names = new FieldList(['persons_id', 'title', 'gender', 'title_string', 'personal_name', 'dating', 'dating_note', 'region', 'region_note', 'note']);
    }

    protected function parse() {
        //This should be implemented in child classes to parse data after retrieving from the database
        $this->parseNote(['region_note', 'dating_note', 'note']);
    }

    protected function loadChildren() {

        $filterPersonBonds = new Filter([New Rule('persons_id', 'exact', $this->getID(), 'i')]);
        $objPersonBonds = New PersonBonds(NULL, 0, 0, $filterPersonBonds);
        $this->data['bonds'] = $objPersonBonds;

        $filterAtt = new Filter([New Rule('persons_id', 'exact', $this->getID(), 'i')]);
        $objAtt = New PersonAttestations(NULL, 0, 0, $filterAtt);

        $total = count($objAtt->data);
        for ($i = 0; $i < $total; $i++) {
            $filter = new Filter([New Rule('attestations_id', 'exact', $objAtt->data[$i]['attestations_id'], 'i')]);
            $objSpellings = New ObjectSpellings(NULL, 0, 0, $filter);
            $objAtt->data[$i]['spellings'] = $objSpellings;

            $objTitles = New ObjectTitles(NULL, 0, 0, $filter);
            $objAtt->data[$i]['titles'] = $objTitles;


            $filterBonds = new Filter([New Rule('attestations_id', 'exact', $objAtt->data[$i]['attestations_id'])]);
            $objBonds = New ObjectBonds(NULL, 0, 0, $filterBonds);
            $objAtt->data[$i]['bonds'] = $objBonds;
        }
        $this->data['attestations'] = $objAtt;
         
    }

}
