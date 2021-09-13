<?php

/*
 * Description of InscriptionAttestation
 * A model for the attestations of personal names appearing on a particular inscribed object
 */

namespace PNM\models;


class ObjectInscription extends ListModel
{

 protected $tablename = 'objects_inscriptions_xref INNER JOIN inscriptions ON objects_inscriptions_xref.inscriptions_id = inscriptions.inscriptions_id';
    public $defaultsort = 'title_sort';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['objects_inscriptions_xref.inscriptions_id','title'], ['inscriptions_id','title']);
    }
}
