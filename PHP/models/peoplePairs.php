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
 *
 */
class peoplePairs extends ListModel
{

    protected $double_params = true;
    public $type = "double";
    public $defaultsort = 'title';

    const SELLIST = 'att_a.inscriptions_id as inscriptions_id, att_a.id as id, att_a.gender as gender, att_a.title_string as title_string, att_a.title_string_sort as title_string_sort, att_a.personal_name as personal_name, att_a.personal_name_sort as personal_name_sort, att_b.gender as gender_b, att_b.title_string as title_string_b, att_b.title_string_sort as title_string_sort_b, att_b.personal_name as personal_name_b, att_b.personal_name_sort as personal_name_sort_b, att_a.object_type as object_type, att_a.title as title, att_a.title_sort as title_sort, att_a.dating as dating, att_a.dating_sort as dating_sort, att_a.region as region, att_a.region_temp_sort as region_temp_sort';
    const NOPERSONS = ' WHERE not (exists(SELECT attestations_id FROM persons_attestations_xref WHERE status = "accepted" AND persons_attestations_xref.attestations_id = att_a.attestations_id)=1 AND exists(SELECT attestations_id FROM persons_attestations_xref WHERE status = "accepted" AND persons_attestations_xref.attestations_id = att_b.attestations_id)=1)';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['inscriptions_id', 'id', 'gender', 'title_string', 'title_string_sort', 'personal_name', 'personal_name_sort', 'gender_b', 'title_string_b', 'title_string_sort_b', 'personal_name_b', 'personal_name_sort_b', 'object_type', 'title', 'title_sort', 'dating', 'dating_sort', 'region', 'region_temp_sort']);
    }

    protected function makeSQL($inputsort, $start, $count)
    {
        if (empty($inputsort) || $inputsort == $this->defaultsort) {
            $sort = null;
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
        if ($this->params == 'persons_only') {
            $this->double_params = false;
            return $this->makeSelectFromWherePersons() . $ORDER . $LIMIT;
        } else {
            return $this->makeSelectFromWhere() . $ORDER . $LIMIT;
        }
    }

    protected function makeSelectFromWhere()
    {
        //to be used in child classes
    }

    protected function makeSelectFromWherePersons()
    {
        //to be used in child classes
    }

    protected function getSortField($sortField = null)
    {
        if (empty($sortField)) {
            $sortField = $this->defaultsort;
        }
        return $this->replaceSortField($sortField, ['title', 'dating', 'title_string', 'personal_name', 'title_string_b', 'personal_name_b', 'region'], ['title_sort', 'dating_sort', 'title_string_sort', 'personal_name_sort', 'title_string_sort_b', 'personal_name_sort_b', 'region_temp_sort']);
    }
}
