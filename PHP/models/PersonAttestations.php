<?php

namespace PNM\models;

/*
 * Description of PersonAttestations
 * A model for attestations of a particular person
 *
 */

class PersonAttestations extends ListModel
{

    protected $tablename = '(attestations INNER JOIN persons_attestations_xref ON persons_attestations_xref.attestations_id = attestations.attestations_id) INNER JOIN inscriptions on inscriptions.inscriptions_id = attestations.inscriptions_id';
    public $defaultsort = 'FIELD(persons_attestations_xref.status, "accepted", "weak", "rejected"), inscriptions.title_sort';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['inscriptions.inscriptions_id', 'object_type', 'inscriptions.title', 'attestations.attestations_id', 'gender', 'title_string', 'personal_name', 'persons_attestations_xref.status', 'persons_attestations_xref.reasoning', 'persons_attestations_xref.note'], ['inscriptions_id', 'object_type', 'title', 'attestations_id', 'gender', 'title_string', 'personal_name', 'status', 'reasoning', 'note']);
    }
}
