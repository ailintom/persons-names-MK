<?php

/*
 * Description of titles
 * This is a model used to represent the records for titles
 */

namespace PNM\models;

class titles extends ListModel
{

    protected $tablename = 'titles';
    public $defaultsort = 'title';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['titles_id', 'title', 'gender', 'SELECT Count(attestations_id) FROM titles_att WHERE titles_att.titles_id=titles.titles_id', 'usage_period', 'usage_area', 'ward_fischer', 'hannig', 'taylor', 'ayedi','translation_en'], ['titles_id', 'title', 'gender', 'count_attestations', 'usage_period', 'usage_area', 'ward_fischer', 'hannig', 'taylor', 'ayedi','translation_en']);
    }
        protected function getSortField($sortField = null)
    {
        if (empty($sortField)) {
            $sortField = $this->defaultsort;
        }
        return $this->replaceSortField($sortField, ['title', 'ward_fischer', 'hannig', 'taylor', 'ayedi', 'usage_area', 'usage_period'], ['title_sort', 'ward_fischer_sort', 'hannig_sort', 'taylor_sort', 'ayedi_sort','usage_area_sort', 'usage_period_sort']);
    }
}
