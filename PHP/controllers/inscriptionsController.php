<?php

/*
 * Description of inscriptionsController
 * This controller is used to search for inscriptions
 *
 */

namespace PNM\controllers;

use \PNM\Request,
    \PNM\models\Rule,
    \PNM\models\RuleExists,
    \PNM\models\Filter,
    \PNM\Config;

class inscriptionsController
{

    public function load()
    {
        $rules = [];
        if (!empty(Request::get('collection'))) {
            $collID = \PNM\models\Lookup::collectionsGet(Request::get('collection'));
            if (empty($collID)) {
                array_push($rules, new Rule('0', 'exact', 1, 'i'));
            } elseif (!empty(Request::get('title'))) {
                $inv = str_replace("*", "%", Request::get('title'));
                array_push($rules, new RuleExists('inv_nos WHERE inv_nos.objects_id = objects.objects_id '
                        . 'AND inv_nos.collections_id = ? AND inv_nos.inv_no LIKE ? ', [$collID, $inv], 'is'));
            } else {
                array_push($rules, new RuleExists('inv_nos WHERE inv_nos.objects_id = objects.objects_id AND inv_nos.collections_id =?', $collID, 'i'));
            }
        } elseif (!empty(Request::get('title'))) {
            array_push($rules, new Rule('title', 'exactlike', Request::get('title')));
        }
        if (!empty(Request::get('object_subtype'))) {
            array_push($rules, new Rule('object_subtype', 'exact', Request::get('object_subtype')));
        } elseif (!empty(Request::get('object_type'))) {
            array_push($rules, new Rule('object_type', 'exact', Request::get('object_type')));
        }
        if (!empty(Request::get('material'))) {
            array_push($rules, new Rule('material', 'exact', Request::get('material')));
        }
        if (!empty(Request::get('text_content'))) {
            array_push($rules, new Rule('text_content', 'exact', Request::get('text_content')));
        }
        if (!empty(Request::get('script'))) {
            array_push($rules, new Rule('script', 'exact', Request::get('script')));
        }
        if (!empty(Request::get('size'))) {
            if (Request::get('size-option') == 'less') {
                array_push($rules, new Rule('GREATEST(IFNULL(length,0), IFNULL(height,0), IFNULL(width,0), IFNULL(thickness,0))', 'lessorequal', Request::get('size'), 'i'));
                array_push($rules, new Rule('GREATEST(IFNULL(length,0), IFNULL(height,0), IFNULL(width,0), IFNULL(thickness,0))', 'not', 0, 'i'));
            } elseif (Request::get('size-option') == 'greater') {
                array_push($rules, new Rule('GREATEST(IFNULL(length,0), IFNULL(height,0), IFNULL(width,0), IFNULL(thickness,0))', 'moreorequal', Request::get('size'), 'i'));
            }
        }
        if (!empty(Request::get('place'))) {
            switch (Request::get('geo-filter')) {
                case 'origin':
                    array_push($rules, new Rule('origin', 'exact', Request::get('place')));
                    break;
                case 'provenance':
                    array_push($rules, new Rule('provenance', 'exact', Request::get('place')));
                    break;
                case 'installation':
                    array_push($rules, new Rule('installation_place', 'exact', Request::get('place')));
                    break;
                case 'production':
                    array_push($rules, new Rule('production_place', 'exact', Request::get('place')));
                    break;
                case 'all':
                default:
                    array_push($rules, new Rule(['origin', 'provenance', 'installation_place', 'production_place'], 'exact', Request::get('place')));
            }
        }
        if (!empty(Request::get('period'))) {
            $periodEnd = \PNM\models\Lookup::dateEnd(Request::get('period'));
            $periodStart = \PNM\models\Lookup::dateStart(Request::get('period'));
            $periodType = \PNM\models\Lookup::get('SELECT thesaurus FROM thesauri WHERE item_name = ?', Request::get('period'));
            if (empty($periodStart) || empty($periodEnd)) {
                array_push($rules, new Rule('0', 'exact', 1, 'i'));
            } else {
                switch (Request::get('chrono-filter')) {
                    case 'during':
                        array_push($rules, new Rule('dating_sort_start', 'lessorequal', $periodEnd, 'i'));
                        array_push($rules, new Rule('dating_sort_end', 'moreorequal', $periodStart, 'i'));
                        break;
                    case 'strictly':
                        if ($periodType == 6) {
                            array_push($rules, new Rule('dating', 'exact', Request::get('period'), 's'));
                        } else {
                            array_push($rules, new Rule('dating_sort_start', 'moreorequal', $periodStart, 'i'));
                            array_push($rules, new Rule('dating_sort_end', 'lessorequal', $periodEnd, 'i'));
                        }
                        break;
                    case 'not-later':
                        array_push($rules, new Rule('dating_sort_start', 'lessorequal', $periodEnd, 'i'));
                        break;
                    case 'not-earlier':
                        array_push($rules, new Rule('dating_sort_end', 'moreorequal', $periodStart, 'i'));
                        break;
                }
            }
        }
        $filter = new Filter($rules);
        $inscriptions = new \PNM\models\inscriptions(Request::get('sort'), (Request::get('start') ?: 0), Config::ROWS_ON_PAGE, $filter); //$sort = null, $start = 0, $count = 0, Filter $filter = null
        $view = new \PNM\views\inscriptionsView();
        $view->echoRender($inscriptions);
    }
}
