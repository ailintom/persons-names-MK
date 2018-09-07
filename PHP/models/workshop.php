<?php

/*
 * Description of workshop
 * A model for single workshop
 *
 */

namespace PNM\models;

class workshop extends EntryModel
{

    protected $tablename = 'workshops';
    protected $hasBiblio = true;
    protected $idField = 'workshops_id';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['workshops_id', 'title', 'production_place', 'production_place_note', 'dating', 'dating_note', 'note']);
    }

    protected function parse()
    {
        $this->parseNote(['dating_note', 'note']);
    }


}
