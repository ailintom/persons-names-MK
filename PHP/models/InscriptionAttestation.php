<?php

/*
 * Description of InscriptionAttestation
 * A model for the attestations of personal names appearing on a particular inscribed object
 */

namespace PNM\models;

class InscriptionAttestation extends ListModel
{

    protected $tablename = 'attestations';
    public $defaultsort = 'location, attestations_id';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['attestations_id', 'location', 'gender', 'title_string', 'personal_name', 'status', 'epithet', 'classifier', 'representation', 'note']);
    }
}
