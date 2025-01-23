<?php

/*
 * Description of people
 * A model for people
 * This is the model used when only one person is being searched for
 *
 */

namespace PNM\models;

class people extends ListModel {

    protected $double_params = true;
    public $type = "single";
    public $defaultsort = 'title';
    protected $query1 = 'inscriptions.inscriptions_id AS inscriptions_id, attestations_id AS id, gender, title_string, title_string_sort, personal_name, personal_name_sort, (SELECT objects.object_type FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1) as object_type, title, title_sort, dating, (dating_sort_start+dating_sort_end) as dating_sort, (region_temp) AS region, region_temp_sort FROM (select * from attestations where exists(SELECT attestations_id FROM persons_attestations_xref WHERE `status` = "accepted" AND persons_attestations_xref.attestations_id = attestations.attestations_id)=0) as attestations INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id';
    protected $query2 = 'DISTINCT 0 as inscriptions_id, persons.persons_id as id, gender, title_string, title_string_sort, personal_name, personal_name_sort, "Dossier" as object_type, title, title_sort, dating, (dating_sort_start+dating_sort_end) as dating_sort, region, persons.region_sort as region_temp_sort FROM persons INNER JOIN (SELECT persons_attestations_xref.attestations_id as attestations_id, persons_id, inscriptions_id from persons_attestations_xref INNER JOIN attestations on persons_attestations_xref.attestations_id=attestations.attestations_id WHERE persons_attestations_xref.`status`="accepted") AS attestations ON persons.persons_id = attestations.persons_id';

    protected function initFieldNames() {
        $this->field_names = new FieldList(['inscriptions_id', 'id', 'gender', 'title_string', 'title_string_sort', 'personal_name', 'personal_name_sort', 'object_type', 'title', 'title_sort', 'dating', 'dating_sort', 'region']);
    }

//'gender_b', 'title_string_b', 'personal_name_b',
    protected function makeSQL($inputsort, $start, $count, $selectStatement = 'SELECT SQL_CALC_FOUND_ROWS ') {
        if (empty($inputsort) || $inputsort == $this->defaultsort) {
            $sort = null;
        } else {
              $sort = $this->getSortField($inputsort);
           /*
              if (!((0 === substr_compare($sort, " DESC", -5)) or (0 === substr_compare($sort, " ASC", -4)))){
                $sort = null;
            }else{
                $sortr = trim(substr($sort, 0, -4));
                if (preg_match('/[^a-zA-Z_]/', $sortr)){
                    $sort = null;
                }
            }
            * 
            */
        }
        if (!empty($sort . $this->getSortField())) {
            $ORDER = ' ORDER BY ' . $sort . (empty($sort) || empty($this->getSortField()) ? null : ', ') . $this->getSortField();
        } else {
            $ORDER = null;
        }
        if ($start > 0 || $count > 0) {
            $LIMIT = ' LIMIT ' . $start . ', ' . ($count > 0 ? $count : self::MAX_RECORD_COUNT);
        } else {
            $LIMIT = null;
        }
        if ($this->params == 'persons_only') {
            $this->double_params = false;
            return $this->makeSelectFromWherePersons($selectStatement) . $ORDER . $LIMIT;
        } else {
            return $this->makeSelectFromWhere($selectStatement) . $ORDER . $LIMIT;
        }
    }

    protected function makeSelectFromWhere($selectStatement) {
        return '(' . $selectStatement . $this->query1 . $this->WHERE . ') UNION ALL (SELECT ' . $this->query2 . $this->WHERE . ')';
    }

    protected function makeSelectFromWherePersons($selectStatement) {
        return $selectStatement . $this->query2 . $this->WHERE;
    }

    protected function getSortField($sortField = null) {
        if (empty($sortField)) {
            $sortField = $this->defaultsort;
        }
        return $this->replaceSortField($sortField, ['title', 'dating', 'title_string', 'personal_name', 'region'], ['title_sort', 'dating_sort', 'title_string_sort', 'personal_name_sort', 'region_temp_sort']);
    }

}
