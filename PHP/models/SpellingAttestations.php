<?php

/*
 * Description of SpellingAttestations
 * A model for attestations of a particular spelling
 *
 */

namespace PNM\models;

class SpellingAttestations extends ListModel
{

    protected $tablename = 'spellings_attestations_xref INNER JOIN (attestations INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) ON attestations.attestations_id = spellings_attestations_xref.attestations_id';
    public $defaultsort = 'title_sort';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['inscriptions.inscriptions_id', 'attestations.attestations_id', 'gender', 'title', 'SELECT objects.object_type FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1', 'title_string', 'SELECT objects.provenance FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1', 'SELECT objects.installation_place FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1', 'origin', 'SELECT objects.production_place FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1', 'dating',
            'SELECT count(persons.persons_id) FROM persons_attestations_xref INNER JOIN persons ON persons_attestations_xref.persons_id = persons.persons_id WHERE persons_attestations_xref.attestations_id = attestations.attestations_id'], ['inscriptions_id', 'attestations_id',
            'gender', 'title', 'object_type', 'title_string', 'provenance', 'installation_place', 'origin', 'production_place', 'dating', 'persons_count']);
    }
}
