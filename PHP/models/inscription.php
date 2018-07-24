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

namespace PNM\models;

class inscription extends EntryModel
{

    protected $tablename = 'inscriptions';
    protected $hasBiblio = true;
    protected $idField = 'inscriptions_id';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['inscriptions_id', 'title', 'object_type', 'object_subtype', 'material', 'length', 'height', 'width', 'thickness', 'find_groups_id', 'text_content',
            'script', 'provenance', 'provenance_note', 'installation_place', 'installation_place_note', 'origin', 'origin_note', 'production_place', 'production_place_note',
            'dating', 'dating_note', 'last_king_id', 'note']);
    }

    protected function parse()
    {
        //This should be implemented in child classes to parse data after retrieving from the database
        $this->parseNote(['provenance_note', 'installation_place_note', 'origin_note', 'production_place_note', 'dating_note', 'note']);
        //inv_nos
        $mainRule = new Rule('inscriptions_id', 'exact', $this->getID(), 'i');
        $filter = new Filter([$mainRule, new Rule('status', 'exact', 'main', 's')]);
        $objInv_nos = new ObjectInv_nos(null, 0, 0, $filter); //$sort = null, $start = 0, $count = 0, Filter $filter = null
        if (!empty($objInv_nos->data)) {
            $this->processInvNos($objInv_nos->data, "inv_no");
        }
        $filter = new Filter([$mainRule, new Rule('status', 'exact', 'alternative', 's')]);
        $objInv_nos = new ObjectInv_nos(null, 0, 0, $filter); //$sort = null, $start = 0, $count = 0, Filter $filter = null
        if (!empty($objInv_nos->data)) {
            $this->processInvNos($objInv_nos->data, "alternative_inv_no");
        }
        $filter = new Filter([$mainRule, new Rule('status', 'exact', 'obsolete', 's')]);
        $objInv_nos = new ObjectInv_nos(null, 0, 0, $filter); //$sort = null, $start = 0, $count = 0, Filter $filter = null
        if (!empty($objInv_nos->data)) {
            $this->processInvNos($objInv_nos->data, "obsolete_inv_no");
        }
        $filter = new Filter([$mainRule, new Rule('status', 'exact', 'erroneous', 's')]);
        $objInv_nos = new ObjectInv_nos(null, 0, 0, $filter); //$sort = null, $start = 0, $count = 0, Filter $filter = null
        if (!empty($objInv_nos->data)) {
            $this->processInvNos($objInv_nos->data, "erroneous_inv_no");
        }
        //collections.collections_id', 'title', 'inv_no', 'status'], ['collections_id', 'title', 'inv_no', 'status']);
    }

    protected function loadChildren()
    {
        $filterAtt = new Filter([new Rule('inscriptions_id', 'exact', $this->getID(), 'i')]);
        $objWk = new InscriptionWorkshops(null, 0, 0, $filterAtt);
        $this->data['workshops'] = $objWk;
        $objAtt = new ObjectAttestations(null, 0, 0, $filterAtt);
        $total = count($objAtt->data);
        for ($i = 0; $i < $total; $i++) {
            //foreach ($objAtt->data as $Att) {
            $filter = new Filter([new Rule('attestations_id', 'exact', $objAtt->data[$i]['attestations_id'], 'i')]);
            $objSpellings = new ObjectSpellings(null, 0, 0, $filter);
            $objAtt->data[$i]['spellings'] = $objSpellings;
            $objTitles = new ObjectTitles(null, 0, 0, $filter);
            // print_r($objTitles->data);
            $objAtt->data[$i]['titles'] = $objTitles;
            $rulesAttPersons = [new Rule('attestations_id', 'exact', $objAtt->data[$i]['attestations_id'], 'i')];
            $filterAttPersons = new Filter($rulesAttPersons);
            $objAttPersons = new AttestationPersons(null, 0, 0, $filterAttPersons);
            $objAtt->data[$i]['persons'] = $objAttPersons;
            $filterBonds = new Filter([new Rule('attestations_id', 'exact', $objAtt->data[$i]['attestations_id'], 'i')]);
            $objBonds = new ObjectBonds(null, 0, 0, $filterBonds);
            $objAtt->data[$i]['bonds'] = $objBonds;
        }
        $this->data['attestations'] = $objAtt;
        // print_r($objAtt);
    }

    protected function processInvNos($data, $field)
    {
        $sortedData = $data;
        usort($sortedData, function ($a, $b) {
            return strnatcasecmp($a['title'], $b['title']) == 0 ? strnatcasecmp($a['inv_no'], $b['inv_no']) : strnatcasecmp($a['title'], $b['title']);
        });
        $title = null;
        $invs = null;
        $res = null;
        $concat = ($field == 'inv_no' ? '+' : ', ');
        $colView = new \PNM\views\collectionsMicroView();
        $invView = new \PNM\views\inv_nosMicroView();
        foreach ($sortedData as $row) {
            if ($title !== $row['title']) {
                if (!empty($invs)) {
                    $res .= (empty($res) ? null : $concat) . $colView->render($title, $id) . ' ' . $invs;
                }
                $title = $row['title'];
                $id = $row['collections_id'];
                $invs = $invView->render($row['inv_no']);
            } else {
                $invs .= (empty($invs) ? null : $concat) . $invView->render($row['inv_no']);
            }
        }
        if (!empty($invs)) {
            $res .= (empty($res) ? null : $concat) . $colView->render($title, $id) . ' ' . $invs;
        }
        $this->data[$field] = $res;
    }
}
