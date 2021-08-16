<?php

/*
 * Description of namesController
 * This controller is used to search for personal names
 *
 */

namespace PNM\controllers;

use \PNM\Request,
    \PNM\models\Rule,
    \PNM\models\RuleExists,
    \PNM\models\Filter,
    \PNM\Config,
    \PNM\models\Lookup;

class namesController
{

    public function load()
    {

        $rules = [];
        if (!empty(Request::get('name'))) {
            array_push($rules, new Rule('personal_name_search', Request::get('match') ?: Request::DEFAULTS['match'], Translit::searchVal(Request::get('name'))));
        }
        if (!empty(Request::get('translation'))) {
            array_push($rules, new Rule(['translation_en', 'translation_de'], Request::get('match') ?: Request::DEFAULTS['match'], Request::get('translation')));
        }
        if (!empty(Request::get('gender')) && Request::get('gender') != 'any') {
            array_push($rules, new Rule('gender', 'exact', Request::get('gender')));
        }
        if (!empty(Request::get('ranke'))) {
            array_push($rules, new Rule('ranke', 'inexact', Request::get('ranke')));
        }

        if (!empty(Request::get('period')) && Request::get('match-date') == 'characteristic') {
            array_push($rules, new Rule('usage_period', 'exact', Request::get('period')));
        } elseif (!empty(Request::get('period'))) {
            $periodEnd = Lookup::dateEnd(Request::get('period'));
            $periodStart = Lookup::dateStart(Request::get('period'));
            $periodType = \PNM\models\Lookup::get('SELECT thesaurus FROM thesauri WHERE item_name = ?', Request::get('period'));
            if (empty($periodStart) || empty($periodEnd)) {
                array_push($rules, new Rule('0', 'exact', 1, 'i'));
            } elseif (Request::get('match-date') == 'strictly' && $periodType == 6) {
                array_push($rules, new RuleExists(' ((spellings INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id)'
                        . ' INNER JOIN attestations ON spellings_attestations_xref.attestations_id = attestations.attestations_id) '
                        . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id'
                        . ' WHERE spellings.personal_names_id=personal_names.personal_names_id  AND '
                        . ' inscriptions.dating = ?', Request::get('period'), 's'));
            } elseif (Request::get('match-date') == 'strictly') {
                array_push($rules, new RuleExists(' ((spellings INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id)'
                        . ' INNER JOIN attestations ON spellings_attestations_xref.attestations_id = attestations.attestations_id) '
                        . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id'
                        . ' WHERE spellings.personal_names_id=personal_names.personal_names_id  AND '
                        . ' inscriptions.dating_sort_end <= ? AND '
                        . ' inscriptions.dating_sort_start >= ?', [$periodEnd, $periodStart], 'ii'));
            } else {
                array_push($rules, new RuleExists(' ((spellings INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id)'
                        . ' INNER JOIN attestations ON spellings_attestations_xref.attestations_id = attestations.attestations_id) '
                        . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id'
                        . ' WHERE spellings.personal_names_id=personal_names.personal_names_id  AND '
                        . ' inscriptions.dating_sort_end >= ? AND '
                        . ' inscriptions.dating_sort_start <= ?', [$periodStart, $periodEnd], 'ii'));
            }
        }

        $place = Request::get('place');
        if (!empty($place) && Request::get('match-region') == 'characteristic') {
            array_push($rules, new Rule('usage_area', 'exact', $place));
        } elseif ($place == 'NUELE') {
            array_push($rules, new RuleExists('(((spellings INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id)'
                    . ' INNER JOIN attestations ON spellings_attestations_xref.attestations_id = attestations.attestations_id) '
                    . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) '
                    . ' INNER JOIN places ON region_temp = places.place_name'
                    . ' WHERE spellings.personal_names_id=personal_names.personal_names_id AND '
                    . ' places.macro_region IN(?, ?, ?)', ["ME", "MFR", "LE"], 'sss'));
        } elseif (in_array($place, ['Nubia', 'SUE', 'ME', 'MFR', 'LE'])) {
            array_push($rules, new RuleExists('(((spellings INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id)'
                    . ' INNER JOIN attestations ON spellings_attestations_xref.attestations_id = attestations.attestations_id) '
                    . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) '
                    . ' INNER JOIN places ON region_temp = places.place_name'
                    . ' WHERE spellings.personal_names_id=personal_names.personal_names_id AND '
                    . ' places.macro_region = ?', $place, 's'));
        } elseif (!empty($place)) {
            array_push($rules, new RuleExists(' ((spellings INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id)'
                    . ' INNER JOIN attestations ON spellings_attestations_xref.attestations_id = attestations.attestations_id) '
                    . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id'
                    . ' WHERE spellings.personal_names_id=personal_names.personal_names_id  AND '
                    . ' region_temp=?', $place, 's'));
        }
        if (!empty(Request::get('form_type'))) {
            $nt = Lookup::name_types_idGet(Request::get('form_type'));
            if (!empty($nt)) {
                array_push($rules, new RuleExists('names_types_xref INNER JOIN name_types_temp ON names_types_xref.name_types_id = name_types_temp.child_id'
                        . ' WHERE names_types_xref.personal_names_id=personal_names.personal_names_id AND '
                        . ' name_types_temp.parent_id = ?', $nt, 'i'));
            }
        }
        if (!empty(Request::get('sem_type'))) {
            $nt = Lookup::name_types_idGet(Request::get('sem_type'));
            if (!empty($nt)) {
                array_push($rules, new RuleExists('names_types_xref INNER JOIN name_types_temp ON names_types_xref.name_types_id = name_types_temp.child_id'
                        . ' WHERE names_types_xref.personal_names_id=personal_names.personal_names_id AND '
                        . ' name_types_temp.parent_id = ?', $nt, 'i'));
            }
        }

        $filter = new Filter($rules);
        $model = new \PNM\models\names(Request::get('sort'), (Request::get('start') ?: 0), Config::ROWS_ON_PAGE, $filter);
        $view = new \PNM\views\namesView();
        $view->echoRender($model);
    }
}
