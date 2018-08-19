<?php

/*
 * Description of criterion
 * A model for a single criterion
 */

namespace PNM\models;

class criterion extends EntryModel
{

    protected $tablename = 'criteria';
    protected $hasBiblio = true;
    protected $idField = 'criteria_id';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['criteria_id', 'title', 'criterion', 'production_place', 'dating']);
    }

    protected function parse()
    {
        if (empty($this->data['criterion'])) {
            $this->data['criterion'] = '&nbsp';
        }
        //This should be implemented in child classes to parse data after retrieving from the database
        //$this->parseNote(['provenance_note', 'installation_place_note', 'origin_note', 'production_place_note', 'dating_note', 'note']);
    }
}
