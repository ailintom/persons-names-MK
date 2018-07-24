<?php

/*
 * MIT License
 *
 * Copyright (c) 2017 Alexander Ilin-Tomich (unless specified otherwise for individual source files and documents)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace PNM;

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
    private function getAttNo($objNameSpellings, $spelling_no, $att_no_in_spelling)
    {
        if ($spelling_no > 0) {
            $cnt = $objNameSpellings->data[$spelling_no - 1]['first_no'] + $objNameSpellings->data[$spelling_no - 1]['attestations']->count;
        } else {
            $cnt = 0;
        }
        return $cnt + $att_no_in_spelling;
    }
}
