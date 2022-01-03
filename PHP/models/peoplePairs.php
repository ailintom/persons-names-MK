<?php

/*
 * Description of peoplePairs
 * A model for people
 * This is the model used when two persons are being searched for 
 * The particular relation between the two persons defines the use of one of the child classes
 *
 */

namespace PNM\models;

class peoplePairs extends people
{

    protected $double_params = true;
    public $type = "double";
    public $defaultsort = 'title';

    const SELLIST = 'att_a.inscriptions_id as inscriptions_id, att_a.id as id, att_a.gender as gender, att_a.title_string as title_string, att_a.title_string_sort as title_string_sort, att_a.personal_name as personal_name, att_a.personal_name_sort as personal_name_sort, att_b.gender as gender_b, att_b.title_string as title_string_b, att_b.title_string_sort as title_string_sort_b, att_b.personal_name as personal_name_b, att_b.personal_name_sort as personal_name_sort_b, att_a.object_type as object_type, att_a.title as title, att_a.title_sort as title_sort, att_a.dating as dating, att_a.dating_sort as dating_sort, att_a.region as region, att_a.region_temp_sort as region_temp_sort';
    const NOPERSONS = ' WHERE not (exists(SELECT attestations_id FROM persons_attestations_xref WHERE `status` = "accepted" AND persons_attestations_xref.attestations_id = att_a.attestations_id)=1 AND exists(SELECT attestations_id FROM persons_attestations_xref WHERE `status` = "accepted" AND persons_attestations_xref.attestations_id = att_b.attestations_id)=1)';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['inscriptions_id', 'id', 'gender', 'title_string', 'title_string_sort', 'personal_name', 'personal_name_sort', 'gender_b', 'title_string_b', 'title_string_sort_b', 'personal_name_b', 'personal_name_sort_b', 'object_type', 'title', 'title_sort', 'dating', 'dating_sort', 'region', 'region_temp_sort']);
    }

    protected function getSortField($sortField = null)
    {
        if (empty($sortField)) {
            $sortField = $this->defaultsort;
        }
        return $this->replaceSortField($sortField, ['title', 'dating', 'title_string', 'personal_name', 'title_string_b', 'personal_name_b', 'region'], ['title_sort', 'dating_sort', 'title_string_sort', 'personal_name_sort', 'title_string_sort_b', 'personal_name_sort_b', 'region_temp_sort']);
    }
}
