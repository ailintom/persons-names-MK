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
class NameSpellings extends ListModel
{

    protected $tablename = 'spellings';
    public $defaultsort = 'count_attestations DESC, spelling_norm ASC';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['spellings_id', 'spelling', 'ranke', 'SELECT Count(DISTINCT attestations_id) FROM spellings_attestations_xref WHERE spellings.spellings_id=spellings_attestations_xref.spellings_id', 'usage_period', 'usage_area', 'usage_period_note', 'usage_area_note'], ['spellings_id', 'spelling', 'ranke', 'count_attestations', 'usage_period', 'usage_area', 'usage_period_note', 'usage_area_note']);
    }

    protected function loadChildren()
    {
        $total = count($this->data);
        for ($i = 0; $i < $total; $i++) {
            $filter = new Filter([new Rule('spellings_id', 'exact', $this->data[$i]['spellings_id'], 'i')]);
            $objAltReadings = new ObjectAltReadings(null, 0, 0, $filter);
            $this->data[$i]['alt_readings'] = $objAltReadings;
            $rulesAtt = [new Rule('spellings_id', 'exact', $this->data[$i]['spellings_id'], 'i')];
            $filterSpellAtt = new Filter($rulesAtt);
            $objSpellAtt = new SpellingAttestations(null, 0, 0, $filterSpellAtt);
            $this->data[$i]['attestations'] = $objSpellAtt;
        }
    }
}
