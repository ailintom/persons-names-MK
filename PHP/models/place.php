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

class place extends EntryModel
{

    protected $tablename = 'places';
    protected $hasBiblio = false;
    protected $idField = 'places_id';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList([
            'places_id', 'place_name', 'long_place_name', 'relative_location', 'macro_region', 'latitude', 'topbib_id', 'tm_geoid', 'pleiades_id', 'artefacts_url',
            'SELECT COUNT(inscriptions_id) from inscriptions WHERE inscriptions.provenance=places.place_name',
            'SELECT COUNT(inscriptions_id) from inscriptions WHERE inscriptions.installation_place=places.place_name',
            'SELECT COUNT(inscriptions_id) from inscriptions WHERE inscriptions.origin=places.place_name',
            'SELECT COUNT(inscriptions_id) from inscriptions WHERE inscriptions.production_place=places.place_name'
                ], [
            'places_id', 'place_name', 'long_place_name', 'relative_location', 'macro_region', 'latitude', 'topbib_id', 'tm_geoid', 'pleiades_id', 'artefacts_url',
            'count_provenance', 'count_installation_place', 'count_origin', 'count_production_place']);
    }

    protected function parse()
    {
        $this->data['count_total'] = $this->data['count_provenance'] + $this->data['count_installation_place'] + $this->data['count_origin'] + $this->data['count_production_place'];
        //This should be implemented in child classes to parse data after retrieving from the database
        //$this->parseNote(['provenance_note', 'installation_place_note', 'origin_note', 'production_place_note', 'dating_note', 'note']);
    }

   
}
