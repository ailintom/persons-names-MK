<?php

/*
 * Description of inscription
 * A model used to load an inscribed object and child records from other tables
 */

namespace PNM\models;

class inscription extends EntryModel
{

    protected $tablename = 'inscriptions';
    protected $hasBiblio = true;
    protected $idField = 'inscriptions_id';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['inscriptions_id', 'title', 'topbib_id', 'object_type', 'object_subtype', 'material', 'length', 'height', 'width', 'thickness', 'find_groups_id', 'text_content',
            'script', 'provenance', 'provenance_note', 'installation_place', 'installation_place_note', 'origin', 'origin_note', 'production_place', 'production_place_note',
            'dating', 'dating_note', 'last_king_id', 'note']);
    }

    protected function parse()
    {

        $this->parseNote(['provenance_note', 'installation_place_note', 'origin_note', 'production_place_note', 'dating_note', 'note']);
        //inv_nos
        $mainRule = new Rule('inscriptions_id', 'exact', $this->getID(), 'i');
        $filter = new Filter([$mainRule, new Rule('status', 'exact', 'main', 's')]);
        $this->data['inv_no'] = new InscriptionInv_nos(null, 0, 0, $filter);


        $filter = new Filter([$mainRule, new Rule('status', 'exact', 'alternative', 's')]);
        $this->data['alternative_inv_no'] = new InscriptionInv_nos(null, 0, 0, $filter);

        $filter = new Filter([$mainRule, new Rule('status', 'exact', 'obsolete', 's')]);
        $this->data['obsolete_inv_no'] = new InscriptionInv_nos(null, 0, 0, $filter);

        $filter = new Filter([$mainRule, new Rule('status', 'exact', 'erroneous', 's')]);
        $this->data['erroneous_inv_no'] = new InscriptionInv_nos(null, 0, 0, $filter);
    }

    protected function loadChildren()
    {
        $filterAtt = new Filter([new Rule('inscriptions_id', 'exact', $this->getID(), 'i')]);
        $objWk = new InscriptionWorkshops(null, 0, 0, $filterAtt);
        $this->data['workshops'] = $objWk;
        $objAtt = new InscriptionAttestation(null, 0, 0, $filterAtt);
        $total = count($objAtt->data);
        for ($i = 0; $i < $total; $i++) {

            $filter = new Filter([new Rule('attestations_id', 'exact', $objAtt->data[$i]['attestations_id'], 'i')]);
            $objSpellings = new AttestationSpellings(null, 0, 0, $filter);
            $objAtt->data[$i]['spellings'] = $objSpellings;
            $objTitles = new AttestationTitles(null, 0, 0, $filter);
            // print_r($objTitles->data);
            $objAtt->data[$i]['titles'] = $objTitles;
            $rulesAttPersons = [new Rule('attestations_id', 'exact', $objAtt->data[$i]['attestations_id'], 'i')];
            $filterAttPersons = new Filter($rulesAttPersons);
            $objAttPersons = new AttestationPersons(null, 0, 0, $filterAttPersons);
            $objAtt->data[$i]['persons'] = $objAttPersons;
            $filterBonds = new Filter([new Rule('attestations_id', 'exact', $objAtt->data[$i]['attestations_id'], 'i')]);
            $objBonds = new bonds(null, 0, 0, $filterBonds);
            $objAtt->data[$i]['bonds'] = $objBonds;
        }
        $this->data['attestations'] = $objAtt;
        // print_r($objAtt);
    }
}
