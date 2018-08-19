<?php

/*
 * Description of type
 * A model for a single name type
 *
 */

namespace PNM\models;

class type extends EntryModel
{

    protected $tablename = 'name_types';
    protected $hasBiblio = true;
    protected $idField = 'name_types_id';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['name_types_id', 'parent_id', 'title', 'IF(EXISTS(SELECT * FROM name_types_temp where name_types_temp.child_id = name_types.name_types_id AND name_types_temp.parent_id = 251658604 ), "semantic", "formal")', 'note',
            'SELECT Count(DISTINCT attestations_id) FROM '
            . ' (((name_types_temp INNER JOIN names_types_xref ON name_types_temp.child_id = names_types_xref.name_types_id) INNER JOIN personal_names ON names_types_xref.personal_names_id = personal_names.personal_names_id) INNER JOIN spellings ON personal_names.personal_names_id = spellings.personal_names_id) INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id WHERE name_types_temp.parent_id=name_types.name_types_id'], ['name_types_id',
            'parent_id', 'title', 'category', 'note', 'attestations_count']);
    }

    protected function parse()
    {
        //This should be implemented in child classes to parse data after retrieving from the database
        $this->parseNote(['note']);
    }

    protected function loadChildren()
    {
        $id = $this->get('parent_id');
        while (!empty($id)) {
            $parents[] = [$id, Lookup::get('SELECT title FROM name_types WHERE name_types_id = ?', $id)];
            $id = Lookup::get('SELECT parent_id FROM name_types WHERE name_types_id = ?', $id);
        }
        if (!empty($parents)) {
            $this->data['parents'] = $parents;
        }
    }
}
