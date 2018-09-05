<?php

namespace PNM\controllers;

class titleController extends EntryController
{

    const NAME = 'title';

    protected function loadChildren()
    {
        $rules = [new \PNM\models\Rule('titles_id', 'exact', $this->record->get('titles_id'), 'i')];
        //geo-filter
        if (!empty(\PNM\Request::get('place'))) {
            if (\PNM\Request::get('geo-filter') == 'any') {
                if (in_array(\PNM\Request::get('place'), ['Nubia', 'SUE', 'ME', 'MFR', 'LE', 'NUELE'])) {
                    $geoField = ['(SELECT macro_region FROM places WHERE places.place_name = inscriptions.provenance)',
                        '(SELECT macro_region FROM places WHERE places.place_name = inscriptions.installation_place)',
                        '(SELECT macro_region FROM places WHERE places.place_name = inscriptions.origin)',
                        '(SELECT macro_region FROM places WHERE places.place_name = inscriptions.production_place)'];
                } else {
                    $geoField = ['provenance', 'installation_place', 'origin', 'production_place'];
                }
            } else {
                if (in_array(\PNM\Request::get('place'), ['Nubia', 'SUE', 'ME', 'MFR', 'LE', 'NUELE'])) {
                    $geoField = '(SELECT macro_region FROM places WHERE places.place_name = inscriptions.' . \PNM\Request::get('geo-filter') . ')';
                } else {
                    $geoField = \PNM\Request::get('geo-filter');
                }
            }
            if (\PNM\Request::get('place') == 'NUELE') {
                $place = ["ME", "MFR", "LE"];
            } else {
                $place = \PNM\Request::get('place');
            }
            array_push($rules, new \PNM\models\Rule($geoField, 'exact', $place));
        }
        if (!empty(\PNM\Request::get('period')) && \PNM\Request::get('chrono-filter') == 'not-later') {
            array_push($rules, new \PNM\models\Rule('dating_sort_start', \PNM\Request::get('chrono-filter'), \PNM\models\Lookup::dateEnd(\PNM\Request::get('period')), 'i'));
        } elseif (!empty(\PNM\Request::get('period')) && \PNM\Request::get('chrono-filter') == 'not-earlier') {
            array_push($rules, new \PNM\models\Rule('dating_sort_end', \PNM\Request::get('chrono-filter'), \PNM\models\Lookup::dateStart(\PNM\Request::get('period')), 'i'));
        } elseif (!empty(\PNM\Request::get('period'))) {
            $periodEnd = \PNM\models\Lookup::dateEnd(\PNM\Request::get('period'));
            $periodStart = \PNM\models\Lookup::dateStart(\PNM\Request::get('period'));
            if (empty($periodStart) || empty($periodEnd)) {
                array_push($rules, new \PNM\models\Rule('0', 'exact', 1, 'i'));
            } else {
                array_push($rules, new \PNM\models\Rule('dating_sort_start', 'not-later', $periodEnd, 'i'));
                array_push($rules, new \PNM\models\Rule('dating_sort_end', 'not-earlier', $periodStart, 'i'));
            }
            // array_push($rules, new \PNM\models\Rule('Exists(SELECT child_item_name FROM dating_temp '
            //        . 'WHERE dating_temp.child_item_name = inscriptions.dating '
            //       . 'AND dating_temp.parent_item_name="' . \PNM\Request::get('period') . '")', 'exact', $res, 'i'));
            //array_push($rules, new \PNM\models\Rule('dating', 'exact', \PNM\Request::get('period')));
        }
        $filterTitleAtt = new \PNM\models\Filter($rules);
        $objTitleAtt = new \PNM\models\TitleAttestations(\PNM\Request::get('sort'), (\PNM\Request::get('start') ?: 0), \PNM\Config::ROWS_ON_PAGE, $filterTitleAtt);
        $this->record->data['attestations'] = $objTitleAtt;
        $filterRelations = new \PNM\models\Filter([new \PNM\models\Rule('titles_id', 'exact', $this->record->get('titles_id'), 'i'), new \PNM\models\Rule('titles_id', 'exact', $this->record->get('titles_id'), 'i')]);
        $objRelations = new \PNM\models\title_relations(null, 0, 0, $filterRelations);
        $this->record->data['relations'] = $objRelations;
    }
}
