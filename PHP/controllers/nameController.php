<?php

/*
 * Description of nameController
 * This controller is used to load a personal name including all attestations and persons
 *
 */

namespace PNM\controllers;

use \PNM\models\Rule,
    \PNM\models\Filter,
    \PNM\models\NameSpellings,
    \PNM\models\NamePersons,
    \PNM\models\AttestationPersons;

class nameController extends EntryController
{

    const NAME = 'name';

    protected function loadChildren()
    {
        $rules = [new Rule('personal_names_id', 'exact', $this->record->get('personal_names_id'), 'i')];
        $filterNameSpellings = new Filter($rules);
        $objNameSpellings = new NameSpellings(null, 0, 0, $filterNameSpellings);
        $filterPersons = new Filter($rules);
        $objNamePersons = new NamePersons(null, 0, 0, $filterPersons);
        $totalSpells = count($objNameSpellings->data);
        for ($i = 0; $i < $totalSpells; $i++) {
            $totalAtts = count($objNameSpellings->data[$i]['attestations']->data);
            for ($j = 0; $j < $totalAtts; $j++) {
                if ($objNameSpellings->data[$i]['attestations']->data[$j]['persons_count'] > 0) {
                    $rulesAttPersons = [new Rule('attestations_id', 'exact', $objNameSpellings->data[$i]['attestations']->data[$j]['attestations_id'], 'i'),
                        new Rule('status', 'not', 'rejected', 's')];
                    $filterAttPersons = new Filter($rulesAttPersons);
                    $objAttPersons = new AttestationPersons(null, 0, 0, $filterAttPersons);
                    foreach ($objAttPersons->data as $attPerson) {
                        $personId = $attPerson['persons_id'];
                        $personKey = array_search($personId, array_column($objNamePersons->data, 'persons_id'));
                        $persDesc['attestations_id'] = $objNameSpellings->data[$i]['spellings_id'] . '_' . $objNameSpellings->data[$i]['attestations']->data[$j]['attestations_id'];
                        $persDesc['att_no'] = $this->getAttNo($objNameSpellings, $i, $j + 1);
                        $objNamePersons->data[$personKey]['attestations'][] = $persDesc;
                    }
                    $objNameSpellings->data[$i]['attestations']->data[$j]['persons'] = $objAttPersons;
                }
                $objNameSpellings->data[$i]['first_no'] = $this->getAttNo($objNameSpellings, $i, 1); //Calculate the first number for the section of the numbered list
            }
        }
        $this->record->data['spellings'] = $objNameSpellings;
        $this->record->data['persons'] = $objNamePersons;
    }

    // Caclulated the number of a particular attestation in the numbered list of attestations divided into spellings
    private function getAttNo(NameSpellings &$objNameSpellings, $spelling_no, $att_no_in_spelling)
    {
        if ($spelling_no > 0) {
            $cnt = $objNameSpellings->data[$spelling_no - 1]['first_no'] + $objNameSpellings->data[$spelling_no - 1]['attestations']->count - 1;
        } else {
            $cnt = 0;
        }

        return $cnt + $att_no_in_spelling;
    }
}
