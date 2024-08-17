<?php

namespace PNM\controllers;

use \PNM\Request,
    \PNM\models\Rule,
    \PNM\models\Filter,
    \PNM\Config;

class titleController extends EntryController {

    const NAME = 'title';

    protected function loadChildren() {
        $rules = [new Rule('titles_id', 'exact', $this->record->get('titles_id'), 'i')];
        //geo-filter
        $geoFields = ["production_place" => "(SELECT objects.production_place FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1)",
            "origin" => "inscriptions.origin",
            "provenance" => "(SELECT objects.provenance FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1)",
            "installation_place" => "(SELECT objects.installation_place FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1)",];
        if (!empty(Request::get('place'))) {
            if (Request::get('geo-filter') == 'any') {
                if (in_array(Request::get('place'), ['Nubia', 'SUE', 'ME', 'MFR', 'LE', 'NUELE'])) {
                    $geoField = array_walk($geoFields, function (&$value, $key) {
                        $value = "(SELECT macro_region FROM places WHERE places.place_name = $value)";
                    });
                } else {
                    $geoField = array_values($geoFields);
                }
            } else {
                if (in_array(Request::get('place'), ['Nubia', 'SUE', 'ME', 'MFR', 'LE', 'NUELE'])) {
                    $geoField = '(SELECT macro_region FROM places WHERE places.place_name = ' . $geoFields[Request::get('geo-filter')] . ')';
                } else {
                    $geoField = $geoFields[Request::get('geo-filter')];
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
        $filterSpellings = new Filter([new Rule('titles_id', 'exact', $this->record->get('titles_id'), 'i'), new Rule('CHAR_LENGTH(spelling)', 'not', 0, 'i')]);
// $objFG = new \PNM\models\find_groups(Request::get('find_groups_sort'), 0, 0, $filterFG);
        $objSpellings = new \PNM\models\title_spellings(null, 0, 0, $filterSpellings);
        $this->record->data['spellings'] = $objSpellings;
    }

}
