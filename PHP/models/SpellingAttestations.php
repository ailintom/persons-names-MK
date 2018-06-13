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
class SpellingAttestations extends ListModel
{

    protected $tablename = 'spellings_attestations_xref INNER JOIN (attestations INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) ON attestations.attestations_id = spellings_attestations_xref.attestations_id';
    public $defaultsort = 'title_sort';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['inscriptions.inscriptions_id', 'attestations.attestations_id', 'gender', 'title', 'object_type', 'title_string', 'provenance', 'installation_place', 'origin', 'production_place', 'dating',
            'SELECT count(persons.persons_id) FROM persons_attestations_xref INNER JOIN persons ON persons_attestations_xref.persons_id = persons.persons_id WHERE persons_attestations_xref.attestations_id = attestations.attestations_id'], ['inscriptions_id', 'attestations_id',
            'gender', 'title', 'object_type', 'title_string', 'provenance', 'installation_place', 'origin', 'production_place', 'dating', 'persons_count']);
    }
}
