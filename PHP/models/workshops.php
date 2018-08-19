<?php

/*
 * Description of workshops
 * This is a model used to represent the records for workshops
 */

namespace PNM\models;

class workshops extends ListModel
{

    protected $tablename = 'workshops';
    public $defaultsort = 'title';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['workshops_id', 'title', 'dating', 'dating_sort_start+dating_sort_end', 'SELECT count(inscriptions_id) FROM inscriptions_workshops_xref WHERE status<>"rejected" AND inscriptions_workshops_xref.workshops_id=workshops.workshops_id'], ['workshops_id', 'title', 'dating', 'dating_sort', 'inscriptions_count']);
    }

    protected function getSortField($sortField = null)
    {
        if (empty($sortField)) {
            $sortField = $this->defaultsort;
        }
        return $this->replaceSortField($sortField, ['title', 'dating'], ['title_sort', 'dating_sort']);
    }
}
