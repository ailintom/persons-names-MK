<?php

/*
 * Description of alternative_readings
 * A model for the alternative readings of a name  
 */

namespace PNM\models;

class alternative_readings extends ListModel
{

    protected $tablename = 'alternative_readings INNER JOIN personal_names ON alternative_readings.personal_names_id = personal_names.personal_names_id';
    public $defaultsort = 'personal_name_sort';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['personal_names.personal_names_id', 'personal_names.personal_name'], ['personal_names_id', 'personal_name']);
    }
}
