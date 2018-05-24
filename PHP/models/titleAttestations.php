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
class titleAttestations extends ListModel {

    protected $tablename = '(titles_att INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id) INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id';
    public $defaultsort = 'personal_name';

    protected function initFieldNames() {
        $this->field_names = new FieldList(['attestations.inscriptions_id', 'attestations.attestations_id', 'personal_name', 'gender', 'title_string', 'title', 'dating',
            'CASE WHEN origin>"" THEN CONCAT(origin, " (origin)") WHEN production_place>"" THEN CONCAT(production_place, " (production)") WHEN installation_place>"" THEN CONCAT(installation_place, " (installation_place)") WHEN provenance>"" THEN CONCAT(provenance, " (provenance)") END',
            'SELECT count(attestations_id) FROM persons_attestations_xref WHERE status <> "rejected" AND persons_attestations_xref.attestations_id = attestations.attestations_id', 'SELECT GROUP_CONCAT(CONCAT(title, " (", SUBSTR(status, 1,1), ")") SEPARATOR "; ") 
FROM persons_attestations_xref INNER JOIN persons ON persons_attestations_xref.persons_id = persons.persons_id
WHERE  persons_attestations_xref.attestations_id = attestations.attestations_id'], ['inscriptions_id', 'attestations_id', 'personal_name', 'gender', 'title_string', 'title', 'dating', 'region', 'count_persons', 'persons']);
    }

    protected function getSortField($sortField = NULL) {
        if (empty($sortField)) {
            $sortField = $this->defaultsort;
        }
        return $this->replaceSortField($sortField, ['title_string', 'title', 'personal_name',
                    'persons', 'dating', 'region'], ['title_string_sort', 'title_sort', 'personal_name_sort',
                    'count_persons', 'dating_sort_start+dating_sort_end', 'region_temp_sort']);
    }

}
