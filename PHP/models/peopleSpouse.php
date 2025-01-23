<?php

/*
 * Description of peopleSpouse
 * A model for people
 * This is the model used when a person being a spouse of another person is being searched for
 *
 */

namespace PNM\models;

class peopleSpouse extends peoplePairs
{

    protected $query1a = 'SELECT attestations_id, attestations_id AS id, inscriptions.inscriptions_id AS inscriptions_id, gender, title_string, title_string_sort, personal_name, personal_name_sort, (SELECT objects.object_type FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1) as object_type, title, title_sort, dating, (dating_sort_start+dating_sort_end) as dating_sort, (region_temp) AS region, region_temp_sort FROM attestations INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id';
    protected $query1b = 'SELECT attestations_id, spouses_temp.second_id as second_id, attestations_id AS id, inscriptions.inscriptions_id AS inscriptions_id, gender, title_string, title_string_sort, personal_name, personal_name_sort, (SELECT objects.object_type FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1) as object_type, title, title_sort, dating, (dating_sort_start+dating_sort_end) as dating_sort, (region_temp) AS region FROM ( attestations INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) INNER JOIN spouses_temp ON attestations.attestations_id = spouses_temp.first_id';
    protected $query2a = 'SELECT DISTINCT persons.persons_id as id, 0 as inscriptions_id, gender, title_string, title_string_sort, personal_name, personal_name_sort, "Dossier" as object_type, title, title_sort, dating, (dating_sort_start+dating_sort_end) as dating_sort, region, persons.region_sort as region_temp_sort FROM persons INNER JOIN (SELECT persons_attestations_xref.attestations_id as attestations_id, persons_id, inscriptions_id from persons_attestations_xref INNER JOIN attestations on persons_attestations_xref.attestations_id=attestations.attestations_id WHERE persons_attestations_xref.`status`="accepted") AS attestations ON persons.persons_id = attestations.persons_id';
    protected $query2b = 'SELECT DISTINCT spouses_temp.second_id as second_id, persons.persons_id as id, 0 as inscriptions_id, gender, title_string, title_string_sort, personal_name, personal_name_sort, "Dossier" as object_type, title, title_sort, dating, (dating_sort_start+dating_sort_end) as dating_sort, region FROM (persons INNER JOIN (SELECT persons_attestations_xref.attestations_id as attestations_id, persons_id, inscriptions_id from persons_attestations_xref INNER JOIN attestations on persons_attestations_xref.attestations_id=attestations.attestations_id WHERE persons_attestations_xref.`status`="accepted") AS attestations ON persons.persons_id = attestations.persons_id) INNER JOIN spouses_temp ON persons.persons_id = spouses_temp.first_id';

    protected function makeSelectFromWhere($selectStatement)
    {
        return '(' . $selectStatement . static::SELLIST . ' FROM (' . $this->query1a . $this->WHERE . ') as att_a INNER JOIN (' . $this->query1b . $this->BWHERE . ') as att_b ON att_a.attestations_id = att_b.second_id' . static::NOPERSONS . ') UNION ALL '
                . '(SELECT ' . static::SELLIST . ' FROM (' . $this->query2a . $this->WHERE . ') as att_a INNER JOIN (' . $this->query2b . $this->BWHERE . ') as att_b ON att_a.id = att_b.second_id)';
    }

    protected function makeSelectFromWherePersons($selectStatement)
    {
        return '(' . $selectStatement . static::SELLIST . ' FROM (' . $this->query2a . $this->WHERE . ') as att_a INNER JOIN (' . $this->query2b . $this->BWHERE . ') as att_b ON att_a.id = att_b.second_id)';
    }
}
