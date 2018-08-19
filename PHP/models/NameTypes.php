<?php

/*
 * Description of NameTypes
 * A model for the types to which a particular personal name belongs
 */

namespace PNM\models;

class NameTypes extends ListModel
{

    protected $tablename = 'names_types_xref INNER JOIN name_types ON names_types_xref.name_types_id = name_types.name_types_id';
    public $defaultsort = 'title_sort';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['name_types.name_types_id', 'REPLACE(title, "#", "")'], ['name_types_id', 'title']);
    }
}
