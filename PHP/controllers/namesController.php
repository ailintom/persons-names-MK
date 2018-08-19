<?php

/*
 * Description of namesController
 * This controller is used to search for personal names
 *
 */

namespace PNM\controllers;

class namesController
{

    public function load()
    {

        $rules = [];
        if (!empty(\PNM\Request::get('name'))) {
            array_push($rules, new \PNM\models\Rule('personal_name_search', \PNM\Request::get('match') ?: 'exactlike', Translit::searchVal(\PNM\Request::get('name'))));
        }
        if (!empty(\PNM\Request::get('translation'))) {
            array_push($rules, new \PNM\models\Rule(['translation_en', 'translation_de'], \PNM\Request::get('match') ?: 'exactlike', \PNM\Request::get('translation')));
        }
        if (!empty(\PNM\Request::get('gender')) && \PNM\Request::get('gender') != 'any') {
            array_push($rules, new \PNM\models\Rule('gender', 'exact', \PNM\Request::get('gender')));
        }
        if (!empty(\PNM\Request::get('ranke'))) {
            array_push($rules, new \PNM\models\Rule('ranke', 'inexact', \PNM\Request::get('ranke')));
        }
        if (!empty(\PNM\Request::get('place')) && \PNM\Request::get('match-region') == 'characteristic') {
            array_push($rules, new \PNM\models\Rule('usage_area', 'exact', \PNM\Request::get('place')));
        } elseif (in_array(\PNM\Request::get('place'), ['Nubia', 'SUE', 'ME', 'MFR', 'LE', 'NUELE'])) {
            if (\PNM\Request::get('place') == 'NUELE') {
                $reg = ' IN("ME", "MFR", "LE") ';
            } else {
                $reg = '="' . \PNM\Request::get('place') . '"';
            }
            array_push($rules, new \PNM\models\Rule('Exists(SELECT personal_names_id FROM'
                    . ' (((spellings INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id)'
                    . ' INNER JOIN attestations ON spellings_attestations_xref.attestations_id = attestations.attestations_id) '
                    . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) '
                    . ' INNER JOIN places ON region_temp = places.place_name'
                    . ' WHERE spellings.personal_names_id=personal_names.personal_names_id AND '
                    . ' places.macro_region' . $reg . ')', 'exact', 1, 'i'));
        } elseif (!empty(\PNM\Request::get('place'))) {
            array_push($rules, new \PNM\models\Rule('Exists(SELECT personal_names_id FROM'
                    . ' ((spellings INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id)'
                    . ' INNER JOIN attestations ON spellings_attestations_xref.attestations_id = attestations.attestations_id) '
                    . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id'
                    . ' WHERE spellings.personal_names_id=personal_names.personal_names_id  AND '
                    . ' region_temp="' . \PNM\Request::get('place') . '")', 'exact', 1, 'i'));
        }
        if (!empty(\PNM\Request::get('form_type'))) {
            $nt = \PNM\models\Lookup::name_types_idGet(\PNM\Request::get('form_type'));
            if (!empty($nt)) {
                array_push($rules, new \PNM\models\Rule('Exists(SELECT name_types_temp.parent_id '
                        . ' FROM names_types_xref INNER JOIN name_types_temp ON names_types_xref.name_types_id = name_types_temp.child_id'
                        . ' WHERE names_types_xref.personal_names_id=personal_names.personal_names_id AND '
                        . ' name_types_temp.parent_id = ' . $nt . ')', 'exact', 1, 'i'));
            }
        }
        if (!empty(\PNM\Request::get('sem_type'))) {
            $nt = \PNM\models\Lookup::name_types_idGet(\PNM\Request::get('sem_type'));
            if (!empty($nt)) {
                array_push($rules, new \PNM\models\Rule('Exists(SELECT name_types_temp.parent_id '
                        . ' FROM names_types_xref INNER JOIN name_types_temp ON names_types_xref.name_types_id = name_types_temp.child_id'
                        . ' WHERE names_types_xref.personal_names_id=personal_names.personal_names_id AND '
                        . ' name_types_temp.parent_id = ' . $nt . ')', 'exact', 1, 'i'));
            }
        }
        /*
          } elseif (!empty(\PNM\Request::get('period'))) {
          array_push($rules, new \PNM\models\Rule('Exists(SELECT titles_id FROM ((titles_att INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id) '
          . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) '
          . ' INNER JOIN dating_temp ON inscriptions.dating = dating_temp.child_item_name'
          . ' WHERE titles_att.titles_id=titles.titles_id AND '
          . ' dating_temp.parent_item_name="' . \PNM\Request::get('period') . '")', 'exact', 1, 'i'));
          }
         *
         */
        $filter = new \PNM\models\Filter($rules);
        $model = new \PNM\models\names(\PNM\Request::get('sort'), (\PNM\Request::get('start') ?: 0), 50, $filter);
        $view = new \PNM\views\namesView();
        $view->echoRender($model);
    }
}
