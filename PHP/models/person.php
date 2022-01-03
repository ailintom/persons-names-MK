<?php

/*
 * Description of person
 * A model for a single person, including child records
 */

namespace PNM\models;

class person extends EntryModel
{

    protected $tablename = 'persons';
    protected $hasBiblio = true;
    protected $idField = 'persons_id';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['persons_id', 'title', 'gender', 'title_string', 'personal_name', 'dating', 'dating_note', 'region', 'region_note', 'note']);
    }

    protected function parse()
    {
        //This should be implemented in child classes to parse data after retrieving from the database
        $this->parseNote(['region_note', 'dating_note', 'note']);
    }

    protected function loadChildren()
    {
        $filterPersonBonds = new Filter([new Rule('persons_id', 'exact', $this->getID(), 'i')]);
        $objPersonBonds = new PersonBonds(null, 0, 0, $filterPersonBonds, null, null, true);
        $this->data['bonds'] = $objPersonBonds;
        $filterAtt = new Filter([new Rule('persons_id', 'exact', $this->getID(), 'i')]);
        $objAtt = new PersonAttestations(null, 0, 0, $filterAtt, null, null, true);
        $total = count($objAtt->data);
        for ($i = 0; $i < $total; $i++) {
            $filter = new Filter([new Rule('attestations_id', 'exact', $objAtt->data[$i]['attestations_id'], 'i')]);
            $objSpellings = new AttestationSpellings(null, 0, 0, $filter, null, null, true);
            $objAtt->data[$i]['spellings'] = $objSpellings;
            $objTitles = new AttestationTitles(null, 0, 0, $filter, null, null, true);
            $objAtt->data[$i]['titles'] = $objTitles;
            $filterBonds = new Filter([new Rule('attestations_id', 'exact', $objAtt->data[$i]['attestations_id'])]);
            $objBonds = new bonds(null, 0, 0, $filterBonds, null, null, true);
            $objAtt->data[$i]['bonds'] = $objBonds;
        }
        $this->data['attestations'] = $objAtt;
    }
}
