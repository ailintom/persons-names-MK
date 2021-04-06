<?php

/*
 * Description of thesauri
 * A model representing database records for thesauri   
 */

namespace PNM\models;

class thesauri extends ListModel
{

    protected $tablename = 'thesauri';
    public $defaultsort = 'item_name';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['thesauri_id', 'thesaurus', 'parent', 'sort_value', 'item_name', 'external_key', 'explanation']);
    }


    
}
