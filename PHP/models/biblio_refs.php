<?php

/*
 * Description of biblio_refs
 * A model for bibliographic references
 *
 */

namespace PNM\models;

class biblio_refs extends ListModel
{

    protected $tablename = 'biblio_refs';
    public $defaultsort = 'pages_sort';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['biblio_refs_id', 'reference_type', 'object_id', 'pages', 'note']);
    }
}
