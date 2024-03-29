<?php

/*
 * Description of inscription
 * A model used to load an inscription and child records from other tables
 */

namespace PNM\models;

class inscription extends EntryModel {

    protected $tablename = 'inscriptions';
    protected $hasBiblio = true;
    protected $idField = 'inscriptions_id';

    protected function initFieldNames() {
        $this->field_names = new FieldList(['inscriptions_id', 'tmtexts_id', 'tla', 'title', 'text_content',
            'script', 'origin', 'origin_note',
            'dating', 'dating_note', 'last_king_id', 'note']);
    }

//'topbib_id', 'object_type', 'object_subtype', 'material', 'length', 'height', 'width', 'thickness', 'find_groups_id', 'provenance', 'provenance_note', 
    protected function parse() {

        $this->parseNote(['origin_note', 'dating_note', 'note']);
        //inv_nos
        /* $mainRule = new Rule('inscriptions_id', 'exact', $this->getID(), 'i');
          $filter = new Filter([$mainRule, new Rule('`status`', 'exact', 'main', 's')]);
          $this->data['inv_no'] = new InscriptionInv_nos(null, 0, 0, $filter);


          $filter = new Filter([$mainRule, new Rule('`status`', 'exact', 'alternative', 's')]);
          $this->data['alternative_inv_no'] = new InscriptionInv_nos(null, 0, 0, $filter);

          $filter = new Filter([$mainRule, new Rule('`status`', 'exact', 'obsolete', 's')]);
          $this->data['obsolete_inv_no'] = new InscriptionInv_nos(null, 0, 0, $filter);

          $filter = new Filter([$mainRule, new Rule('`status`', 'exact', 'erroneous', 's')]);
          $this->data['erroneous_inv_no'] = new InscriptionInv_nos(null, 0, 0, $filter); */
    }

    protected function loadChildren() {
        //objects_inscriptions_xref
        $filterAtt = new Filter([new Rule('inscriptions_id', 'exact', $this->getID(), 'i')]);
        $objObjects = new InscriptionObject(null, 0, 0, $filterAtt);
        $totalObj = count($objObjects->data);

        for ($i = 0; $i < $totalObj; $i++) {
            $ruleObjChildren = new Rule('objects_id', 'exact', $objObjects->data[$i]['objects_id'], 'i');
            $filterObjChildren = new Filter([$ruleObjChildren]);
            $objWk = new InscriptionWorkshops(null, 0, 0, $filterObjChildren, null, null, true);
            $objObjects->data[$i]['workshops'] = $objWk;
            // echo "<br>totalWk" . count($objWk->data);
            $filterMain = new Filter([$ruleObjChildren, new Rule('`status`', 'exact', 'main', 's')]);
            //print_r($filterMain);
            $objObjects->data[$i]['inv_no'] = new InscriptionInv_nos(null, 0, 0, $filterMain, null, null, true);
            // echo "<br>totalInv" . count($objObjects->data[$i]['inv_no']->data);

            $filterAlt = new Filter([$ruleObjChildren, new Rule('`status`', 'exact', 'alternative', 's')]);
            $objObjects->data[$i]['alternative_inv_no'] = new InscriptionInv_nos(null, 0, 0, $filterAlt, null, null, true);

            $filterObs = new Filter([$ruleObjChildren, new Rule('`status`', 'exact', 'obsolete', 's')]);
            $objObjects->data[$i]['obsolete_inv_no'] = new InscriptionInv_nos(null, 0, 0, $filterObs, null, null, true);

            $filterErr = new Filter([$ruleObjChildren, new Rule('`status`', 'exact', 'erroneous', 's')]);
            $objObjects->data[$i]['erroneous_inv_no'] = new InscriptionInv_nos(null, 0, 0, $filterErr, null, null, true);

            $filterOtherInscriptions = new Filter([$ruleObjChildren, new Rule('objects_inscriptions_xref.inscriptions_id', 'not', $this->getID(), 'i')]);
            $objObjects->data[$i]['inscriptions'] = new ObjectInscription(null, 0, 0, $filterOtherInscriptions, null, null, true);

            $filterBiblio = new Filter([new Rule('object_id', 'exact', $objObjects->data[$i]['objects_id'], 'i')]);
            $objbibliography = new EntryBibliography(null, 0, 0, $filterBiblio, null, null, true);

            $objObjects->data[$i]['bibliography'] = $objbibliography;
        }

        $this->data['objects'] = $objObjects;


        $objAtt = new InscriptionAttestation(null, 0, 0, $filterAtt, null, null, true);
        $total = count($objAtt->data);
        for ($i = 0; $i < $total; $i++) {

            $filter = new Filter([new Rule('attestations_id', 'exact', $objAtt->data[$i]['attestations_id'], 'i')]);
            $objSpellings = new AttestationSpellings(null, 0, 0, $filter, null, null, true);
            $objAtt->data[$i]['spellings'] = $objSpellings;
            $objTitles = new AttestationTitles(null, 0, 0, $filter, null, null, true);
            // print_r($objTitles->data);
            $objAtt->data[$i]['titles'] = $objTitles;
            $rulesAttPersons = [new Rule('attestations_id', 'exact', $objAtt->data[$i]['attestations_id'], 'i')];
            $filterAttPersons = new Filter($rulesAttPersons);
            $objAttPersons = new AttestationPersons(null, 0, 0, $filterAttPersons, null, null, true);
            $objAtt->data[$i]['persons'] = $objAttPersons;
            $filterBonds = new Filter([new Rule('attestations_id', 'exact', $objAtt->data[$i]['attestations_id'], 'i')]);
            $objBonds = new bonds(null, 0, 0, $filterBonds, null, null, true);
            $objAtt->data[$i]['bonds'] = $objBonds;
        }
        $this->data['attestations'] = $objAtt;
        // print_r($objAtt);
    }

}
