<?php

/*
 * Description of object
 * A model used to load an inscribed object  
 */

namespace PNM\models;

class obj extends EntryModel {

    protected $tablename = 'objects';
    protected $hasBiblio = true;
    protected $idField = 'objects_id';

    protected function initFieldNames() {
        $this->field_names = new FieldList(['objects_id', 'title', 'topbib_id',
            'object_type', 'object_subtype', 'material', 'length', 'height',
            'width', 'thickness', 'find_groups_id', 'provenance', 'provenance_note',
            'installation_place', 'installation_place_note', 'production_place', 'production_place_note']);
    }

//'topbib_id', 'object_type', 'object_subtype', 'material', 'length', 'height', 'width', 'thickness', 'find_groups_id', 'provenance', 'provenance_note', 
    protected function parse() {

        $this->parseNote(['provenance_note', 'installation_place_note', 'production_place_note']);
        //inv_nos
        /* $mainRule = new Rule('inscriptions_id', 'exact', $this->getID(), 'i');
          $filter = new Filter([$mainRule, new Rule('status', 'exact', 'main', 's')]);
          $this->data['inv_no'] = new InscriptionInv_nos(null, 0, 0, $filter);


          $filter = new Filter([$mainRule, new Rule('status', 'exact', 'alternative', 's')]);
          $this->data['alternative_inv_no'] = new InscriptionInv_nos(null, 0, 0, $filter);

          $filter = new Filter([$mainRule, new Rule('status', 'exact', 'obsolete', 's')]);
          $this->data['obsolete_inv_no'] = new InscriptionInv_nos(null, 0, 0, $filter);

          $filter = new Filter([$mainRule, new Rule('status', 'exact', 'erroneous', 's')]);
          $this->data['erroneous_inv_no'] = new InscriptionInv_nos(null, 0, 0, $filter); */
    }

    protected function loadChildren() {
        //objects_inscriptions_xref
        $ruleObjChildren = new Rule('objects_id', 'exact', $this->getID(), 'i');
        $filter = new Filter([$ruleObjChildren]);
        $objInscriptions = new ObjectInscription(null, 0, 0, $filter);

        $this->data['inscriptions'] = $objInscriptions;
        $objWk = new InscriptionWorkshops(null, 0, 0, $filter);
        $this->data['workshops'] = $objWk;
        $filterMain = new Filter([$ruleObjChildren, new Rule('status', 'exact', 'main', 's')]);
        $this->data['inv_no'] = new InscriptionInv_nos(null, 0, 0, $filterMain);

        $filterAlt = new Filter([$ruleObjChildren, new Rule('status', 'exact', 'alternative', 's')]);
        $this->data['alternative_inv_no'] = new InscriptionInv_nos(null, 0, 0, $filterAlt);

        $filterObs = new Filter([$ruleObjChildren, new Rule('status', 'exact', 'obsolete', 's')]);
        $this->data['obsolete_inv_no'] = new InscriptionInv_nos(null, 0, 0, $filterObs);

        $filterErr = new Filter([$ruleObjChildren, new Rule('status', 'exact', 'erroneous', 's')]);
        $this->data['erroneous_inv_no'] = new InscriptionInv_nos(null, 0, 0, $filterErr);


        $this->data['inscriptions'] = new ObjectInscription(null, 0, 0, $filter);

        $filterBiblio = new Filter([new Rule('object_id', 'exact', $this->getID(), 'i')]);
        $objbibliography = new EntryBibliography(null, 0, 0, $filterBiblio);

        $this->data['bibliography'] = $objbibliography;
    }

}
