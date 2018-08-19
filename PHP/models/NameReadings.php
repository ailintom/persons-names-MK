<?php

/* Description of NameReadings
 * A model for readings of a particular personal name
 *
 */

namespace PNM\models;

class NameReadings extends ListModel
{

    protected $tablename = ' (alternative_readings INNER JOIN spellings ON alternative_readings.spellings_id = spellings.spellings_id) INNER JOIN personal_names ON spellings.personal_names_id = personal_names.personal_names_id';
    public $defaultsort = 'personal_names.personal_name_sort';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['spellings.spelling', 'spellings.spellings_id', 'personal_names.personal_names_id', 'personal_names.personal_name'], ['spellings.spelling', 'spellings_id', 'personal_names_id', 'personal_name']);
    }
}
