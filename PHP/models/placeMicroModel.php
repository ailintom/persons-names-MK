<?php

/*
 * Description of used to retrieve information on a single place by its name
 */

namespace PNM\models;

class placeMicroModel extends EntryModel
{

    protected $tablename = 'places';
    protected $hasBiblio = false;
    protected $idField = 'place_name';
    protected $bindParam = 's'; //  search by string field

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['places_id', 'place_name', 'long_place_name']);
    }

    protected function validate($id_input)
    {
        return $id_input;
    }
}
