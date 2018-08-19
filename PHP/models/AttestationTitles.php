<?php

namespace PNM\models;

/*
 * Description of AttestationTitles
 * A model for titles associated with a particular attestation of a personal name on an inscribed object
 *
 */

class AttestationTitles extends ListModel
{

    protected $tablename = 'titles INNER JOIN titles_att ON titles.titles_id = titles_att.titles_id';
    public $defaultsort = 'sequence_number';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['titles.titles_id', 'titles.title'], ['titles_id', 'title']);
    }
}
