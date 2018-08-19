<?php

/*
 * Description of names
 * A model representing database records for personal names   
 */

namespace PNM\models;

class names extends ListModel
{

    protected $tablename = 'personal_names';
    public $defaultsort = 'personal_name';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['personal_names.personal_names_id', 'personal_name', 'translation_en',
            'gender', 'ranke', 'usage_area', 'usage_period', 'SELECT count(DISTINCT attestations_id) '
            . 'FROM spellings INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id '
            . 'WHERE spellings.personal_names_id=personal_names.personal_names_id'], ['personal_names_id', 'personal_name', 'translation_en',
            'gender', 'ranke', 'usage_area', 'usage_period', 'count_attestations']);
    }

    protected function getSortField($sortField = null)
    {
        if (empty($sortField)) {
            $sortField = $this->defaultsort;
        }
        return $this->replaceSortField($sortField, ['personal_name', 'ranke'], ['personal_name_sort', 'ranke_sort']);
    }
}
