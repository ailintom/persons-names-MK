<?php

/*
 * Description of AttestationPersons
 * A model used to load persons associated with a particular attestation
 */

namespace PNM\models;

class AttestationPersons extends ListModel
{

    protected $tablename = 'persons_attestations_xref INNER JOIN persons ON persons_attestations_xref.persons_id = persons.persons_id';
    public $defaultsort = 'FIELD(persons_attestations_xref.status, "accepted", "weak", "rejected"), title';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['IF(persons.title>"", persons.title, CONCAT(persons.personal_name, "##"))', 'persons.persons_id', 'status'], ['title', 'persons_id', 'status']);
    }
}
