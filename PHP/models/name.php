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

class name extends EntryModel
{

    protected $tablename = 'personal_names';
    protected $hasBiblio = true;
    protected $idField = 'personal_names_id';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['personal_names_id', 'personal_name', 'gender', 'SELECT Count(DISTINCT attestations_id) FROM spellings JOIN spellings_attestations_xref ON spellings_attestations_xref.spellings_id=spellings.spellings_id WHERE spellings.personal_names_id=personal_names.personal_names_id', 'usage_period', 'usage_area', 'usage_period_note', 'usage_area_note', 'note', 'ranke', '`scheele-schweitzer`', 'tla', 'agea', 'translation_en', 'translation_de'], ['personal_names_id', 'personal_name', 'gender', 'count_attestations', 'usage_period', 'usage_area', 'usage_period_note', 'usage_area_note', 'note', 'ranke', '`scheele-schweitzer`', 'tla', 'agea', 'translation_en', 'translation_de']);
    }

    protected function parse()
    {
        //This should be implemented in child classes to parse data after retrieving from the database
        $this->parseNote(['usage_period_note', 'usage_area_note', 'note']);
    }

    protected function loadChildren()
    {
        $filterTypes = new Filter([new Rule('personal_names_id', 'exact', $this->data['personal_names_id'], 'i')]);
        $this->data['name_types'] = new NameTypes(null, 0, 0, $filterTypes);
        $filterAlt = new Filter([new Rule('alternative_readings.personal_names_id', 'exact', $this->data['personal_names_id'], 'i')]);
        $this->data['alt_readings'] = new NameReadings(null, 0, 0, $filterAlt);
        //
    }
}
