<?php

/*
 * Description of InscriptionAttestation
 * A model for the attestations of personal names appearing on a particular inscribed object
 */

namespace PNM\models;

class InscriptionObject extends ListModel
{

 protected $tablename = 'objects_inscriptions_xref INNER JOIN objects ON objects_inscriptions_xref.objects_id = objects.objects_id';
    public $defaultsort = 'title_sort';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['objects.objects_id','title', 'title_sort', 'topbib_id', 'object_type', 'object_subtype', 'material', 'length', 'height', 'width', 'thickness', 'find_groups_id', 'provenance', 'provenance_note', 'installation_place', 'installation_place_note', 'production_place', 'production_place_note'],
                ['objects_id','title', 'title_sort', 'topbib_id', 'object_type', 'object_subtype', 'material', 'length', 'height', 'width', 'thickness', 'find_groups_id', 'provenance', 'provenance_note', 'installation_place', 'installation_place_note', 'production_place', 'production_place_note']);
    }
}
