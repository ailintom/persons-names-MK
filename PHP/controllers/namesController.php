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

/**
 * Description of namesController
 *
 * @author Tomich
 */
class namesController {

    public function load() {

        $rules = [];
        if (!empty(Request::get('name'))) {
            array_push($rules, new Rule('personal_name_search', Request::get('match') ?: 'exactlike', Translit::sortfromMdCorUnicode(Request::get('name'))));
        }
        if (!empty(Request::get('translation'))) {
            array_push($rules, new Rule(['translation_en', 'translation_de'], Request::get('match') ?: 'exactlike', Request::get('translation')));
        }
        if (!empty(Request::get('gender')) && Request::get('gender') != 'any') {
            array_push($rules, new Rule('gender', 'exact', Request::get('gender')));
        }
        if (!empty(Request::get('ranke'))) {
            array_push($rules, new Rule('ranke', 'inexact', Request::get('ranke')));
        }

        if (!empty(Request::get('place')) && Request::get('match-region') == 'characteristic') {
            array_push($rules, new Rule('usage_area', 'exact', Request::get('place')));
        } elseif (in_array(Request::get('place'), ['Nubia', 'SUE', 'ME', 'MFR', 'LE', 'NUELE'])) {
            if (Request::get('place') == 'NUELE') {
                $reg = ' IN("ME", "MFR", "LE") ';
            } else {
                $reg = '="' . Request::get('place') . '"';
            }
            array_push($rules, new Rule('Exists(SELECT personal_names_id FROM'
                    . ' (((spellings INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id)'
                    . ' INNER JOIN attestations ON spellings_attestations_xref.attestations_id = attestations.attestations_id) '
                    . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) '
                    . ' INNER JOIN places ON COALESCE (inscriptions.origin, inscriptions.production_place, inscriptions.installation_place, inscriptions.provenance) = places.place_name'
                    . ' WHERE spellings.personal_names_id=personal_names.personal_names_id AND '
                    . ' places.macro_region' . $reg . ')', 'exact', 1, 'i'));
        } elseif (!empty(Request::get('place'))) {
            array_push($rules, new Rule('Exists(SELECT personal_names_id FROM'
                    . ' ((spellings INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id)'
                    . ' INNER JOIN attestations ON spellings_attestations_xref.attestations_id = attestations.attestations_id) '
                    . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id'
                    . ' WHERE spellings.personal_names_id=personal_names.personal_names_id  AND '
                    . ' COALESCE (inscriptions.origin, inscriptions.production_place, inscriptions.installation_place, inscriptions.provenance)="' . Request::get('place') . '")', 'exact', 1, 'i'));
        }

        if (!empty(Request::get('name-type-formal'))) {
            $nt = Lookup::name_types_idGet(Request::get('name-type-formal'));
            if (!empty($nt)) {
                array_push($rules, new Rule('Exists(SELECT name_types_temp.parent_id '
                        . ' FROM names_types_xref INNER JOIN name_types_temp ON names_types_xref.name_types_id = name_types_temp.child_id'
                        . ' WHERE names_types_xref.personal_names_id=personal_names.personal_names_id AND '
                        . ' name_types_temp.parent_id = ' . $nt . ')', 'exact', 1, 'i'));
            }
        }
        if (!empty(Request::get('name-type-semantic'))) {
            $nt = Lookup::name_types_idGet(Request::get('name-type-semantic'));
            if (!empty($nt)) {
                array_push($rules, new Rule('Exists(SELECT name_types_temp.parent_id '
                        . ' FROM names_types_xref INNER JOIN name_types_temp ON names_types_xref.name_types_id = name_types_temp.child_id'
                        . ' WHERE names_types_xref.personal_names_id=personal_names.personal_names_id AND '
                        . ' name_types_temp.parent_id = ' . $nt . ')', 'exact', 1, 'i'));
            }
        }


        /*
          } elseif (!empty(Request::get('period'))) {
          array_push($rules, new Rule('Exists(SELECT titles_id FROM ((titles_att INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id) '
          . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) '
          . ' INNER JOIN dating_temp ON inscriptions.dating = dating_temp.child_item_name'
          . ' WHERE titles_att.titles_id=titles.titles_id AND '
          . ' dating_temp.parent_item_name="' . Request::get('period') . '")', 'exact', 1, 'i'));
          }
         * 
         */


        $filter = new Filter($rules);

        $model = New names(Request::get('sort'), (Request::get('start') ?: 0), 50, $filter);


        $view = new namesView ();
        $view->echoRender($model);
    }

}
