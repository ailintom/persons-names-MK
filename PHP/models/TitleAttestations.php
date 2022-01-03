<?php

/*
 * Description of TitleAttestations
 * A model for attestations of a particular title
 */

namespace PNM\models;

class TitleAttestations extends ListModel
{

    protected $tablename = '(titles_att INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id) INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id';
    public $defaultsort = 'personal_name';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['attestations.inscriptions_id', 'attestations.attestations_id', 'personal_name', 'gender', 'title_string', 'title', 'dating',
            'CASE WHEN origin>"" THEN CONCAT(origin, " (origin)") WHEN (SELECT objects.production_place FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1)>"" THEN CONCAT((SELECT objects.production_place FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1), " (production)") WHEN (SELECT objects.installation_place FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1)>"" THEN CONCAT((SELECT objects.installation_place FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1), " (installation_place)") WHEN (SELECT objects.provenance FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1)>"" THEN CONCAT((SELECT objects.provenance FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1), " (provenance)") END',
            'SELECT count(attestations_id) FROM persons_attestations_xref WHERE `status` <> "rejected" AND persons_attestations_xref.attestations_id = attestations.attestations_id', 'SELECT GROUP_CONCAT(CONCAT(title, " (", SUBSTR(`status`, 1,1), ")") SEPARATOR "; ")
FROM persons_attestations_xref INNER JOIN persons ON persons_attestations_xref.persons_id = persons.persons_id
WHERE  persons_attestations_xref.attestations_id = attestations.attestations_id'], ['inscriptions_id', 'attestations_id', 'personal_name', 'gender', 'title_string', 'title', 'dating', 'region', 'count_persons', 'persons']);
    }

    protected function getSortField($sortField = null)
    {
        if (empty($sortField)) {
            $sortField = $this->defaultsort;
        }
        return $this->replaceSortField($sortField, ['title_string', 'title', 'personal_name',
                    'persons', 'dating', 'region'], ['title_string_sort', 'title_sort', 'personal_name_sort',
                    'count_persons', 'dating_sort_start+dating_sort_end', 'region_temp_sort']);
    }
}
