<?php

/*
 * Description of InscriptionWorkshops
 *
 * A model representing workshops with which a particular inscription is associated
 */

namespace PNM\models;

class InscriptionWorkshops extends ListModelTitleSort
{

    protected $tablename = 'workshops INNER JOIN inscriptions_workshops_xref on inscriptions_workshops_xref.workshops_id = workshops.workshops_id';
    public $defaultsort = 'title_sort';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['inscriptions_workshops_xref.note', 'status',
            'workshops.workshops_id', 'title'], ['note', 'status',
            'workshops_id', 'title']);
    }
}
