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
class ObjectSpellings extends ListModel
{

    protected $tablename = '(spellings_attestations_xref INNER JOIN spellings ON spellings_attestations_xref.spellings_id = spellings.spellings_id) INNER JOIN personal_names ON spellings.personal_names_id = personal_names.personal_names_id';
    public $defaultsort = 'spellings_attestations_xref_id';

    //(source_id>0) DESC , source_url, source_title, author_year_sort
    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['spellings.spellings_id', 'spelling', 'personal_names.personal_names_id', 'personal_name'], ['spellings_id', 'spelling', 'personal_names_id', 'personal_name']);
    }

    public function getSpellings()
    {
        $spellings = [];
        foreach ($this->data as $spelling) {
            $index = $this->rowInArray($spelling['personal_names_id'], 'personal_names_id', $spellings);
            if (!isset($index)) {
                $index = array_push($spellings, array('personal_names_id' => $spelling['personal_names_id'], 'personal_name' => $spelling['personal_name'], 'spellings' => [])) - 1;
            }
            $filter = new Filter([new Rule('spellings_id', 'exact', $spelling['spellings_id'], 'i')]);
            $objAltReadings = new ObjectAltReadings(null, 0, 0, $filter);
            array_push($spellings[$index]['spellings'], array('spelling' => $spelling['spelling'], 'spellings_id' => $spelling['spellings_id'], 'alt_readings' => $objAltReadings));
        }
        return $spellings;
    }
}
