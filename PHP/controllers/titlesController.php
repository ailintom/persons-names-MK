<?php

/*
 * Description of titlesController
 * This controller is used to load a single title
 *
 */

namespace PNM\controllers;

class titlesController
{

    public function load()
    {
        $rules = [];
        if (!empty(\PNM\Request::get('title'))) {
            array_push($rules, new \PNM\models\Rule('title_search', \PNM\Request::get('match') ?: 'exactlike', Translit::searchVal(\PNM\Request::get('title'))));
        }
        if (!empty(\PNM\Request::get('translation'))) {
            array_push($rules, new \PNM\models\Rule(['translation_en', 'translation_de'], \PNM\Request::get('match') ?: 'exactlike', \PNM\Request::get('translation')));
        }
        if (!empty(\PNM\Request::get('gender')) && \PNM\Request::get('gender') != 'any') {
            array_push($rules, new \PNM\models\Rule('(CASE 2*EXISTS (SELECT gender FROM titles_att '
                    . 'INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id '
                    . 'WHERE titles_att.titles_id = titles.titles_id AND gender="f") + EXISTS (SELECT gender FROM titles_att '
                    . 'INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id '
                    . 'WHERE titles_att.titles_id = titles.titles_id AND gender="m") WHEN 3 THEN "both" WHEN 2 THEN "f" WHEN 1 THEN "m" END)', 'exact', \PNM\Request::get('gender')));
        }
        if (!empty(\PNM\Request::get('place')) && \PNM\Request::get('match-region') == 'characteristic') {
            array_push($rules, new \PNM\models\Rule('usage_area', 'exact', \PNM\Request::get('place')));
        } elseif (in_array(\PNM\Request::get('place'), ['Nubia', 'SUE', 'ME', 'MFR', 'LE', 'NUELE'])) {
            if (\PNM\Request::get('place') == 'NUELE') {
                $reg = ' IN("ME", "MFR", "LE") ';
            } else {
                $reg = '="' . \PNM\Request::get('place') . '"';
            }
            array_push($rules, new \PNM\models\Rule('Exists(SELECT titles_id FROM'
                    . ' ((titles_att INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id) '
                    . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) '
                    . ' INNER JOIN places ON region_temp = places.place_name'
                    . ' WHERE titles_att.titles_id=titles.titles_id AND '
                    . ' places.macro_region' . $reg . ')', 'exact', 1, 'i'));
        } elseif (!empty(\PNM\Request::get('place'))) {
            array_push($rules, new \PNM\models\Rule('Exists(SELECT titles_id FROM'
                    . ' (titles_att INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id) '
                    . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id'
                    . ' WHERE titles_att.titles_id=titles.titles_id AND '
                    . ' region_temp="' . \PNM\Request::get('place') . '")', 'exact', 1, 'i'));
        }
        /*
          if (!empty(\PNM\Request::get('period')) && \PNM\Request::get('match-date') == 'characteristic') {
          array_push($rules, new \PNM\models\Rule('usage_period', 'exact', \PNM\Request::get('period')));
          } elseif (!empty(\PNM\Request::get('period'))) {
          array_push($rules, new \PNM\models\Rule('Exists(SELECT titles_id FROM ((titles_att INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id) '
          . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) '
          . ' INNER JOIN dating_temp ON inscriptions.dating = dating_temp.child_item_name'
          . ' WHERE titles_att.titles_id=titles.titles_id AND '
          . ' dating_temp.parent_item_name="' . \PNM\Request::get('period') . '")', 'exact', 1, 'i'));
          }
         *
         */
        if (!empty(\PNM\Request::get('period')) && \PNM\Request::get('match-date') == 'characteristic') {
            array_push($rules, new \PNM\models\Rule('usage_period', 'exact', \PNM\Request::get('period')));
        } elseif (!empty(\PNM\Request::get('period'))) {
            $periodEnd = \PNM\models\Lookup::dateEnd(\PNM\Request::get('period'));
            $periodStart = \PNM\models\Lookup::dateStart(\PNM\Request::get('period'));
            if (empty($periodStart) || empty($periodEnd)) {
                array_push($rules, new \PNM\models\Rule('0', 'exact', 1, 'i'));
            } else {
                array_push($rules, new \PNM\models\Rule('Exists(SELECT titles_id FROM ((titles_att '
                        . 'INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id) '
                        . ' INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) '
                        . ' WHERE titles_att.titles_id=titles.titles_id AND '
                        . ' inscriptions.dating_sort_end >= ' . $periodStart . ' AND '
                        . ' inscriptions.dating_sort_start <= ' . $periodEnd . ')', 'exact', 1, 'i'));
            }
        }
        if (!empty(\PNM\Request::get('ward'))) {
            array_push($rules, new \PNM\models\Rule('ward_fischer', 'exact', \PNM\Request::get('ward')));
        }
        if (!empty(\PNM\Request::get('hannig'))) {
            array_push($rules, new \PNM\models\Rule('hannig', 'exact', \PNM\Request::get('hannig')));
        }
        $filter = new \PNM\models\Filter($rules);
        $model = new \PNM\models\titles(\PNM\Request::get('sort'), (\PNM\Request::get('start') ?: 0), 50, $filter);
        $view = new \PNM\views\titlesView();
        $view->echoRender($model);
    }
}
