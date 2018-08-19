<?php

/*
 * Description of InscriptionInv_nos
 * A model of inventory numbers associated with particular inscribed object
 */

namespace PNM\models;

class InscriptionInv_nos extends ListModel
{

    protected $tablename = 'inv_nos INNER JOIN collections ON inv_nos.collections_id = collections.collections_id';
    public $defaultsort = 'title';

//collections.collections_id, collections.title, tblInv_nos.inv_no, tblInv_nos.status
    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['collections.collections_id', 'title', 'inv_no', 'status'], ['collections_id', 'title', 'inv_no', 'status']);
    }
}
