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

class peopleController {

    public function load() {

        $Arules = [];
        if (!empty(Request::get('Agender')) && Request::get('Agender') != 'any') {
            array_push($Arules, new Rule('gender', 'exact', Request::get('Agender')));
        }
        if (!empty(Request::get('Atitle'))) {
            array_push($Arules, new Rule('title_string_search', 'exactlike', Translit::searchVal(Request::get('Atitle'))));
        }
        if (!empty(Request::get('Aname'))) {
            array_push($Arules, new Rule('personal_name_search', 'exactlike', Translit::searchVal(Request::get('Aname'))));
        }
        if (Request::get('only_persons') == 'true') {
            $persons = 'persons_only';
        } else {
            $persons = NULL;
        }
        if (!empty(Request::get('Aform_type'))) {
            $nt = Lookup::name_types_idGet(Request::get('Aform_type'));
            if (!empty($nt)) {
                array_push($Arules, new Rule('Exists(SELECT name_types_temp.parent_id '
                        . ' FROM (((names_types_xref INNER JOIN name_types_temp ON names_types_xref.name_types_id = name_types_temp.child_id) INNER JOIN personal_names ON names_types_xref.personal_names_id = personal_names.personal_names_id) INNER JOIN spellings ON personal_names.personal_names_id = spellings.personal_names_id) INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id'
                        . ' WHERE spellings_attestations_xref.attestations_id=attestations.attestations_id AND '
                        . ' name_types_temp.parent_id = ' . $nt . ')', 'exact', 1, 'i'));
            }else{
                     array_push($Arules, new Rule(1, 'exactlike', 0, 'i'));
                }
        }
        if (!empty(Request::get('Asem_type'))) {
            $nt = Lookup::name_types_idGet(Request::get('Asem_type'));
            if (!empty($nt)) {
                array_push($Arules, new Rule('Exists(SELECT name_types_temp.parent_id '
                        . ' FROM (((names_types_xref INNER JOIN name_types_temp ON names_types_xref.name_types_id = name_types_temp.child_id) INNER JOIN personal_names ON names_types_xref.personal_names_id = personal_names.personal_names_id) INNER JOIN spellings ON personal_names.personal_names_id = spellings.personal_names_id) INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id'
                        . ' WHERE spellings_attestations_xref.attestations_id=attestations.attestations_id AND '
                        . ' name_types_temp.parent_id = ' . $nt . ')', 'exact', 1, 'i'));
            }else{
                     array_push($Arules, new Rule(1, 'exactlike', 0, 'i'));
                }
        }

        if (!empty(Request::get('period'))) {
            $periodEnd = Lookup::dateEnd(Request::get('period'));
            $periodStart = Lookup::dateStart(Request::get('period'));
            if (empty($periodStart) || empty($periodEnd)) {
                array_push($Arules, new Rule('0', 'exact', 1, 'i'));
            } else {
                switch (Request::get('chrono-filter')) {
                    case 'during':
                        $where = ' inscriptions.dating_sort_end >= ' . $periodStart . ' AND inscriptions.dating_sort_start <= ' . $periodEnd;
                        break;
                    case 'not-later':
                        $where = ' inscriptions.dating_sort_start <= ' . $periodEnd;
                        break;
                    case 'not-earlier':
                        $where = ' inscriptions.dating_sort_end >= ' . $periodStart;
                        break;
                }

                array_push($Arules, new Rule('Exists(SELECT inscriptions.inscriptions_id FROM  '
                        . ' inscriptions '
                        . ' WHERE attestations.inscriptions_id=inscriptions.inscriptions_id AND '
                        . $where . ')', 'exact', 1, 'i'));
            }
        }


      
        $filter = new Filter($Arules);
        if (empty(Request::get('Bname')) && empty(Request::get('Btitle')) && empty(Request::get('Bform_type')) && empty(Request::get('Bsem_type')) && (empty(Request::get('Bgender')) || Request::get('Bgender') == 'any')) {
            // second part of the request is not used


            $model = New people(Request::get('sort'), (Request::get('start') ?: 0), 50, $filter, null, $persons);
        } else {
            // second part of the request is used
            $Brules = [];
            if (!empty(Request::get('Bgender')) && Request::get('Bgender') != 'any') {
                array_push($Brules, new Rule('gender', 'exact', Request::get('Bgender')));
            }
            if (!empty(Request::get('Btitle'))) {
                array_push($Brules, new Rule('title_string_search', 'exactlike', Translit::searchVal(Request::get('Btitle'))));
            }
            if (!empty(Request::get('Bname'))) {
                array_push($Brules, new Rule('personal_name_search', 'exactlike', Translit::searchVal(Request::get('Bname'))));
            }
            if (!empty(Request::get('Bform_type'))) {
                $nt = Lookup::name_types_idGet(Request::get('Bform_type'));
                if (!empty($nt)) {
                    array_push($Brules, new Rule('Exists(SELECT name_types_temp.parent_id '
                            . ' FROM (((names_types_xref INNER JOIN name_types_temp ON names_types_xref.name_types_id = name_types_temp.child_id) INNER JOIN personal_names ON names_types_xref.personal_names_id = personal_names.personal_names_id) INNER JOIN spellings ON personal_names.personal_names_id = spellings.personal_names_id) INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id'
                            . ' WHERE spellings_attestations_xref.attestations_id=attestations.attestations_id AND '
                            . ' name_types_temp.parent_id = ' . $nt . ')', 'exact', 1, 'i'));
                }else{
                     array_push($Brules, new Rule(1, 'exactlike', 0, 'i'));
                }
            }
            if (!empty(Request::get('Bsem_type'))) {
                $nt = Lookup::name_types_idGet(Request::get('Bsem_type'));
                if (!empty($nt)) {
                    array_push($Brules, new Rule('Exists(SELECT name_types_temp.parent_id '
                            . ' FROM (((names_types_xref INNER JOIN name_types_temp ON names_types_xref.name_types_id = name_types_temp.child_id) INNER JOIN personal_names ON names_types_xref.personal_names_id = personal_names.personal_names_id) INNER JOIN spellings ON personal_names.personal_names_id = spellings.personal_names_id) INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id'
                            . ' WHERE spellings_attestations_xref.attestations_id=attestations.attestations_id AND '
                            . ' name_types_temp.parent_id = ' . $nt . ')', 'exact', 1, 'i'));
                }else{
                     array_push($Brules, new Rule(1, 'exactlike', 0, 'i'));
                }
            }

            $Bfilter = new Filter($Brules);
            switch (Request::get('relation')) {
                case 'same_inscription':
                    $model = New peopleSameInscr(Request::get('sort'), (Request::get('start') ?: 0), 50, $filter, $Bfilter, $persons);
                    break;
                case 'child':

                    $model = New peopleChild(Request::get('sort'), (Request::get('start') ?: 0), 50, $filter, $Bfilter, $persons);
                    break;
                case 'parent':
                    $model = New peopleParent(Request::get('sort'), (Request::get('start') ?: 0), 50, $filter, $Bfilter, $persons);
                    break;
                case 'spouses':
                    $model = New peopleSpouse(Request::get('sort'), (Request::get('start') ?: 0), 50, $filter, $Bfilter, $persons);
                    break;
                case 'siblings':
                    $model = New peopleSibling(Request::get('sort'), (Request::get('start') ?: 0), 50, $filter, $Bfilter, $persons);
                    break;
            }
        }

        $view = new peopleView ();
        $view->echoRender($model);
    }

}
