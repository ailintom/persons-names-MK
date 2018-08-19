<?php

/*
 * Description of find_groups
 * A model representing database records for find_groups   
 */

namespace PNM\models;

class find_groups extends ListModelTitleSort
{

    protected $tablename = 'find_groups';
    public $defaultsort = 'title';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['find_groups_id', 'title', 'dating', 'find_group_type',
            'SELECT COUNT(inscriptions_id) from inscriptions WHERE inscriptions.find_groups_id=find_groups.find_groups_id'], ['find_groups_id', 'title', 'dating', 'find_group_type', 'inscriptions_count']);
    }

    protected function prepareDefaultSort()
    {
        return 'title_sort';
    }
}
