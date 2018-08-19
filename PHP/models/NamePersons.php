<?php

/* Description of NamePersons
 * A model for persons bearing a particular personal name
 *
 */

namespace PNM\models;

class NamePersons extends ListModel
{

    protected $distinct = 'DISTINCT ';
    protected $tablename = '((spellings_attestations_xref INNER JOIN persons_attestations_xref ON spellings_attestations_xref.attestations_id = persons_attestations_xref.attestations_id) INNER JOIN persons ON persons_attestations_xref.persons_id = persons.persons_id) INNER JOIN spellings ON spellings_attestations_xref.spellings_id = spellings.spellings_id';
    public $defaultsort = 'title';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['persons.persons_id', 'IF(persons.title>"", persons.title, CONCAT(persons.personal_name, "##"))'], ['persons_id', 'title']);
    }
}
