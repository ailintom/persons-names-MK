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
 * Description of people
 * This is a model used when only one person is being searched for
 *
 */
class people extends ListModel {

    protected $double_params = TRUE;

    public $type = "single";
    public $defaultsort = 'title';
    protected $query1 =  'inscriptions.inscriptions_id AS inscriptions_id, attestations_id AS id, gender, title_string, title_string_sort, personal_name, personal_name_sort, object_type, title, title_sort, dating, (dating_sort_start+dating_sort_end) as dating_sort, (region_temp) AS region, region_temp_sort FROM (select * from attestations where exists(SELECT attestations_id FROM persons_attestations_xref WHERE status = "accepted" AND persons_attestations_xref.attestations_id = attestations.attestations_id)=0) as attestations INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id';
    protected $query2 = 'DISTINCT 0 as inscriptions_id, persons.persons_id as id, gender, title_string, title_string_sort, personal_name, personal_name_sort, "Dossier" as object_type, title, title_sort, dating, (dating_sort_start+dating_sort_end) as dating_sort, region, persons.region_sort as region_temp_sort FROM persons INNER JOIN (SELECT persons_attestations_xref.attestations_id as attestations_id, persons_id, inscriptions_id from persons_attestations_xref INNER JOIN attestations on persons_attestations_xref.attestations_id=attestations.attestations_id WHERE persons_attestations_xref.status="accepted") AS attestations ON persons.persons_id = attestations.persons_id';

    protected function initFieldNames() {
        $this->field_names = new FieldList(['inscriptions_id', 'id', 'gender', 'title_string', 'title_string_sort', 'personal_name', 'personal_name_sort','object_type','title', 'title_sort', 'dating', 'dating_sort', 'region']);
    }
//'gender_b', 'title_string_b', 'personal_name_b', 
    protected function makeSQL($inputsort, $start, $count) {
        
        if (empty($inputsort) ||$inputsort == $this->defaultsort) {
            $sort = NULL;
        } else {
            $sort = $this->getSortField($inputsort);
        }
        if (!empty($sort . $this->getSortField())) {
            $ORDER = ' ORDER BY ' . $sort . (empty($sort) || empty($this->getSortField()) ? null : ', ') . $this->getSortField();
        } else {
            $ORDER = null;
        }
        if ($start > 0 || $count > 0) {
            $LIMIT = ' LIMIT ' . $start . ', ' . ($count > 0 ? $count : self::MAX_RECORD_COUNT);
        } else {
            $LIMIT = null;
        }
        if ($this->params == 'persons_only'){
            $this->double_params = FALSE;
            return  'SELECT SQL_CALC_FOUND_ROWS '. $this->query2 . $this->WHERE . $ORDER . $LIMIT;
        }else{
        return '(SELECT SQL_CALC_FOUND_ROWS ' . $this->query1 . $this->WHERE . ') UNION (SELECT ' . $this->query2 . $this->WHERE . ')' . $ORDER . $LIMIT;
        }
    }

    protected function getSortField($sortField = NULL) {
        if (empty($sortField)) {
            $sortField = $this->defaultsort;
        }
        return $this->replaceSortField($sortField, ['title', 'dating', 'title_string', 'personal_name', 'region'], ['title_sort', 'dating_sort', 'title_string_sort', 'personal_name_sort', 'region_temp_sort']);
    }

}
