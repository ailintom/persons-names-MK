<?php

/*
 * Description of bibliography
 * A model for the complete bibliography of publications referred to in the database
 */

namespace PNM\models;

class bibliography extends ListModel
{

    protected $tablename = 'publications';
    public $defaultsort = 'author_year_sort';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['html_entry_truncated', 'publications_id']);
    }
}
