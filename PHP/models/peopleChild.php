<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PNM;

/**
 * Description of peopleChild
 *
 * @author Tomich
 */
class peopleChild extends peoplePairs
{

    protected $query1a = 'SELECT attestations_id, attestations_id AS id, inscriptions.inscriptions_id AS inscriptions_id, gender, title_string, title_string_sort, personal_name, personal_name_sort, object_type, title, title_sort, dating, (dating_sort_start+dating_sort_end) as dating_sort, (region_temp) AS region, region_temp_sort FROM attestations INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id';
    protected $query1b = 'SELECT attestations_id, children_temp.child_id as child_id, attestations_id AS id, inscriptions.inscriptions_id AS inscriptions_id, gender, title_string, title_string_sort, personal_name, personal_name_sort, object_type, title, title_sort, dating, (dating_sort_start+dating_sort_end) as dating_sort, (region_temp) AS region FROM ( attestations INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) INNER JOIN children_temp ON attestations.attestations_id = children_temp.parent_id';
    protected $query2a = 'SELECT DISTINCT persons.persons_id as id, 0 as inscriptions_id, gender, title_string, title_string_sort, personal_name, personal_name_sort, "Dossier" as object_type, title, title_sort, dating, (dating_sort_start+dating_sort_end) as dating_sort, region, persons.region_sort as region_temp_sort FROM persons INNER JOIN (SELECT persons_attestations_xref.attestations_id as attestations_id, persons_id, inscriptions_id from persons_attestations_xref INNER JOIN attestations on persons_attestations_xref.attestations_id=attestations.attestations_id WHERE persons_attestations_xref.status="accepted") AS attestations ON persons.persons_id = attestations.persons_id';
    protected $query2b = 'SELECT DISTINCT children_temp.child_id as child_id, persons.persons_id as id, 0 as inscriptions_id, gender, title_string, title_string_sort, personal_name, personal_name_sort, "Dossier" as object_type, title, title_sort, dating, (dating_sort_start+dating_sort_end) as dating_sort, region FROM (persons INNER JOIN (SELECT persons_attestations_xref.attestations_id as attestations_id, persons_id, inscriptions_id from persons_attestations_xref INNER JOIN attestations on persons_attestations_xref.attestations_id=attestations.attestations_id WHERE persons_attestations_xref.status="accepted") AS attestations ON persons.persons_id = attestations.persons_id) INNER JOIN children_temp ON persons.persons_id = children_temp.parent_id';

    protected function makeSelectFromWhere()
    {
        return '(SELECT SQL_CALC_FOUND_ROWS ' . static::SELLIST . ' FROM (' . $this->query1a . $this->WHERE . ') as att_a INNER JOIN (' . $this->query1b . $this->BWHERE . ') as att_b ON att_a.attestations_id = att_b.child_id' . static::NOPERSONS . ') UNION '
                . '(SELECT ' . static::SELLIST . ' FROM (' . $this->query2a . $this->WHERE . ') as att_a INNER JOIN (' . $this->query2b . $this->BWHERE . ') as att_b ON att_a.id = att_b.child_id)';
    }

    protected function makeSelectFromWherePersons()
    {
        return '(SELECT SQL_CALC_FOUND_ROWS ' . static::SELLIST . ' FROM (' . $this->query2a . $this->WHERE . ') as att_a INNER JOIN (' . $this->query2b . $this->BWHERE . ') as att_b ON att_a.id = att_b.child_id)';
    }
}
