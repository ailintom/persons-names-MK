<?php

/*
 * Description of criterion
 * A model for a single criterion
 */

namespace PNM\models;

class thesaurus extends EntryModel
{

    protected $tablename = 'thesauri';
    protected $hasBiblio = false;
    protected $idField = 'thesauri_id';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['thesauri_id', 'thesaurus', 'parent', 'sort_value', 'item_name', 'external_key', 'explanation']);
    }

    protected function parse()
    {
        if (!empty($this->data['thesaurus'])) {
            $this->data['thesaurus_name'] = Lookup::getThesaurusNane($this->data['thesaurus']);
        }
        if (!empty($this->data['thesaurus'])) {
            $this->data['parent_name'] = Lookup::getThesaurusNane($this->data['parent']);
        }
        //This should be implemented in child classes to parse data after retrieving from the database
        //$this->parseNote(['provenance_note', 'installation_place_note', 'origin_note', 'production_place_note', 'dating_note', 'note']);
    }
}
