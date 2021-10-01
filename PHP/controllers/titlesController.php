<?php

/*
 * Description of titlesController
 * This controller is used to load a single title
 *
 */

namespace PNM\controllers;

use \PNM\Request,
    \PNM\models\Rule,
    \PNM\models\RuleExists,
    \PNM\models\Filter,
    \PNM\Config;

class titlesController
{

    public function load()
    {
        $rules = [];
        if (!empty(Request::get('title'))) {
            array_push($rules, new Rule('title_search', Request::get('match') ?: Request::DEFAULTS['match'], Translit::searchVal(Request::get('title'))));
        }
        if (!empty(Request::get('translation'))) {
            array_push($rules, new Rule(['translation_en', 'translation_de'], Request::get('match') ?: Request::DEFAULTS['match'], Request::get('translation')));
        }
        if (!empty(Request::get('gender')) && Request::get('gender') != 'any') {
            array_push($rules, new Rule('gender', 'exact', Request::get('gender')));
        }
        $place = Request::get('place');
        if (!empty($place) && Request::get('match-region') == 'characteristic') {
            array_push($rules, new Rule('usage_area', 'exact', Request::get('place')));
        } elseif (in_array($place, ['Nubia', 'SUE', 'ME', 'MFR', 'LE'])) {
            array_push($rules, new RuleExists('((titles_att INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id) '
                    . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) '
                    . ' INNER JOIN places ON region_temp = places.place_name'
                    . ' WHERE titles_att.titles_id=titles.titles_id AND '
                    . ' places.macro_region = ?', $place, 's'));
        } elseif ($place == 'NUELE') {
            array_push($rules, new RuleExists('((titles_att INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id) '
                    . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) '
                    . ' INNER JOIN places ON region_temp = places.place_name'
                    . ' WHERE titles_att.titles_id=titles.titles_id AND '
                    . ' places.macro_region IN(?, ?, ?)', ["ME", "MFR", "LE"], 'sss'));
        } elseif (!empty(Request::get('place'))) {

            array_push($rules, new RuleExists('(titles_att INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id) '
                    . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id'
                    . ' WHERE titles_att.titles_id=titles.titles_id AND '
                    . ' region_temp= ?', $place, 's'));
        }

        if (!empty(Request::get('period')) && Request::get('match-date') == 'characteristic') {
            array_push($rules, new Rule('usage_period', 'exact', Request::get('period')));
        } elseif (!empty(Request::get('period'))) {
            $periodEnd = \PNM\models\Lookup::dateEnd(Request::get('period'));
            $periodStart = \PNM\models\Lookup::dateStart(Request::get('period'));
            $periodType = \PNM\models\Lookup::get('SELECT thesaurus FROM thesauri WHERE item_name = ?', Request::get('period'));
            if (empty($periodStart) || empty($periodEnd)) {
                array_push($rules, new Rule('0', 'exact', 1, 'i'));
            } elseif (Request::get('match-date') == 'strictly' && $periodType == 6) {
                array_push($rules, new RuleExists('((titles_att '
                        . 'INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id) '
                        . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) '
                        . ' WHERE titles_att.titles_id=titles.titles_id AND '
                        . ' inscriptions.dating = ?', Request::get('period'), 's'));
            }elseif (Request::get('match-date') == 'strictly') {
                array_push($rules, new RuleExists('((titles_att '
                        . 'INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id) '
                        . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) '
                        . ' WHERE titles_att.titles_id=titles.titles_id AND '
                        . ' inscriptions.dating_sort_end <= ? AND '
                        . ' inscriptions.dating_sort_start >= ?', [$periodEnd, $periodStart], 'ii'));
            } else {
                array_push($rules, new RuleExists('((titles_att '
                        . 'INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id) '
                        . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) '
                        . ' WHERE titles_att.titles_id=titles.titles_id AND '
                        . ' inscriptions.dating_sort_end >= ? AND '
                        . ' inscriptions.dating_sort_start <= ?', [$periodStart, $periodEnd], 'ii'));
            }
        }
        if (!empty(Request::get('ward'))) {
            array_push($rules, new Rule('ward_fischer', 'exact', Request::get('ward')));
        }
        if (!empty(Request::get('hannig'))) {
            array_push($rules, new Rule('hannig', 'exact', Request::get('hannig')));
        }
               if (!empty(Request::get('ayedi'))) {
            array_push($rules, new Rule('ayedi', 'exact', Request::get('ayedi')));
        }
               if (!empty(Request::get('taylor'))) {
            array_push($rules, new Rule('taylor', 'exact', Request::get('taylor')));
        }
        $filter = new Filter($rules);
        $model = new \PNM\models\titles(Request::get('sort'), (Request::get('start') ?: 0), Config::ROWS_ON_PAGE, $filter);
        $view = new \PNM\views\titlesView();
        $view->echoRender($model);
    }
}
