<?php

/*
 * Description of types
 * This is a model used to represent the records for name types
 */

namespace PNM\models;

class types extends ListModelTitleSort
{

    protected $tablename = 'name_types';
    public $defaultsort = 'title';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['name_types_id', 'parent_id', 'REPLACE(title, "#","")'], ['name_types_id', 'parent_id', 'title']);
    }

    protected function loadChildren()
    {
        $total = count($this->data);
        for ($i = 0; $i < $total; $i++) {
            $rules = [new Rule('parent_id', 'exact', $this->data[$i]['name_types_id'], 'i')];
            $filter = new Filter($rules);
            $this->data[$i]['children'] = (new types(null, 0, 0, $filter))->data;
        }
    }
}
