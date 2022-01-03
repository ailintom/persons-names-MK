<?php

namespace PNM\controllers;

use \PNM\Request,
    \PNM\models\Rule,
    \PNM\models\Filter,
    \PNM\Config;

class titleController extends EntryController
{

    const NAME = 'title';

    protected function loadChildren()
    {
        $rules = [new Rule('titles_id', 'exact', $this->record->get('titles_id'), 'i')];
        //geo-filter
        if (!empty(Request::get('place'))) {
            if (Request::get('geo-filter') == 'any') {
                if (in_array(Request::get('place'), ['Nubia', 'SUE', 'ME', 'MFR', 'LE', 'NUELE'])) {
                    $geoField = ['(SELECT macro_region FROM places WHERE places.place_name = inscriptions.provenance)',
                        '(SELECT macro_region FROM places WHERE places.place_name = inscriptions.installation_place)',
                        '(SELECT macro_region FROM places WHERE places.place_name = inscriptions.origin)',
                        '(SELECT macro_region FROM places WHERE places.place_name = inscriptions.production_place)'];
                } else {
                    $geoField = ['provenance', 'installation_place', 'origin', 'production_place'];
                }
            } else {
                if (in_array(Request::get('place'), ['Nubia', 'SUE', 'ME', 'MFR', 'LE', 'NUELE'])) {
                    $geoField = '(SELECT macro_region FROM places WHERE places.place_name = inscriptions.' . Request::get('geo-filter') . ')';
                } else {
                    $geoField = Request::get('geo-filter');
                }
            }
            if (Request::get('place') == 'NUELE') {
                $place = ["ME", "MFR", "LE"];
            } else {
                $place = Request::get('place');
            }
            array_push($rules, new Rule($geoField, 'exact', $place));
        }
        if (!empty(Request::get('period')) && Request::get('chrono-filter') == 'not-later') {
            array_push($rules, new Rule('dating_sort_start', Request::get('chrono-filter'), \PNM\models\Lookup::dateEnd(Request::get('period')), 'i'));
        } elseif (!empty(Request::get('period')) && Request::get('chrono-filter') == 'not-earlier') {
            array_push($rules, new Rule('dating_sort_end', Request::get('chrono-filter'), \PNM\models\Lookup::dateStart(Request::get('period')), 'i'));
        } elseif (!empty(Request::get('period'))) {
            $periodEnd = \PNM\models\Lookup::dateEnd(Request::get('period'));
            $periodStart = \PNM\models\Lookup::dateStart(Request::get('period'));
            $periodType = \PNM\models\Lookup::get('SELECT thesaurus FROM thesauri WHERE item_name = ?', Request::get('period'));
            if (empty($periodStart) || empty($periodEnd)) {
                array_push($rules, new Rule('0', 'exact', 1, 'i'));
            } elseif (Request::get('chrono-filter') == 'strictly' && $periodType == 6) {
                array_push($rules, new Rule('dating', 'exact', Request::get('period'), 's'));
            } elseif (Request::get('chrono-filter') == 'strictly') {
                array_push($rules, new Rule('dating_sort_start', 'not-earlier', $periodStart, 'i'));
                array_push($rules, new Rule('dating_sort_end', 'not-later', $periodEnd, 'i'));
            } else {
                array_push($rules, new Rule('dating_sort_start', 'not-later', $periodEnd, 'i'));
                array_push($rules, new Rule('dating_sort_end', 'not-earlier', $periodStart, 'i'));
            }
            // array_push($rules, new Rule('Exists(SELECT child_item_name FROM dating_temp '
            //        . 'WHERE dating_temp.child_item_name = inscriptions.dating '
            //       . 'AND dating_temp.parent_item_name="' . Request::get('period') . '")', 'exact', $res, 'i'));
            //array_push($rules, new Rule('dating', 'exact', Request::get('period')));
        }
        $filterTitleAtt = new Filter($rules);
        $objTitleAtt = new \PNM\models\TitleAttestations(Request::get('sort'), (Request::get('start') ?: 0), Config::ROWS_ON_PAGE, $filterTitleAtt);
        $this->record->data['attestations'] = $objTitleAtt;
        $filterRelations = new Filter([new Rule('titles_id', 'exact', $this->record->get('titles_id'), 'i'), new Rule('titles_id', 'exact', $this->record->get('titles_id'), 'i')]);
        $objRelations = new \PNM\models\title_relations(null, 0, 0, $filterRelations, null, null, true);
        $this->record->data['relations'] = $objRelations;
    }
}
