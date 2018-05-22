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

class nameController {

    public function load() {

        $record = new name; //'Inscription::find(Request::get('id'));
        $record->find(Request::get('id'));
        $rules = [New Rule('personal_names_id', 'exact', $record->get('personal_names_id'), 'i')];

        $filterNameSpellings = new Filter($rules);
        $objNameSpellings = New NameSpellings(NULL, 0, 0, $filterNameSpellings);

        $filterPersons = new Filter($rules);
        $objNamePersons = New NamePersons(NULL, 0, 0, $filterPersons);

        $totalSpells = count($objNameSpellings->data);
        for ($i = 0; $i < $totalSpells; $i++) {
            $totalAtts = count($objNameSpellings->data[$i]['attestations']->data);
            for ($j = 0; $j < $totalAtts; $j++) {
                if ($objNameSpellings->data[$i]['attestations']->data[$j]['persons_count'] > 0) {
                    $rulesAttPersons = [New Rule('attestations_id', 'exact', $objNameSpellings->data[$i]['attestations']->data[$j]['attestations_id'], 'i'),
                        New Rule('status', 'not', 'rejected', 's')];
                    $filterAttPersons = new Filter($rulesAttPersons);
                    $objAttPersons = New AttestationPersons(NULL, 0, 0, $filterAttPersons);
                    foreach ($objAttPersons->data as $attPerson) {
                        $personId = $attPerson['persons_id'];
                        $personKey = array_search($personId, array_column($objNamePersons->data, 'persons_id'));
                        $persDesc['attestations_id'] = $objNameSpellings->data[$i]['spellings_id'] . '_' . $objNameSpellings->data[$i]['attestations']->data[$j]['attestations_id'];
                        //$persDesc['spelling_no'] = $i;
                        $persDesc['att_no'] = $this->getAttNo($objNameSpellings, $i, $j+1);
                        $objNamePersons->data[$personKey]['attestations'][] = $persDesc;
                    }

                    $objNameSpellings->data[$i]['attestations']->data[$j]['persons'] = $objAttPersons;
                    $objNameSpellings->data[$i]['first_no'] = $this->getAttNo($objNameSpellings, $i, 1);
                }
            }
        }
        $record->data['spellings'] = $objNameSpellings;
        $record->data['persons'] = $objNamePersons;


        (new nameView)->echoRender($record);
    }

    private function getAttNo($objNameSpellings, $spelling_no, $att_no_in_spelling) {
        $cnt = 0;
        for ($i = 0; $i < $spelling_no; $i++) {
            $cnt += $objNameSpellings->data[$i]['attestations']->count;
        }
        return $cnt + $att_no_in_spelling;
    }

}
