<?php

/*
 * Description of inscriptionsController
 * This controller is used to search for inscriptions
 *
 */

namespace PNM\controllers;

class inscriptionsController
{

    public function load()
    {
        $rules = [];
        if (!empty(\PNM\Request::get('collection'))) {
            $collID = \PNM\models\Lookup::collectionsGet(\PNM\Request::get('collection'));
            if (empty($collID)) {
                array_push($rules, new \PNM\models\Rule('0', 'exact', 1, 'i'));
            } elseif (!empty(\PNM\Request::get('title'))) {
                $inv = str_replace("*", "%", \PNM\Request::get('title'));
                array_push($rules, new \PNM\models\RuleExists('inv_nos WHERE inv_nos.inscriptions_id = inscriptions.inscriptions_id '
                        . 'AND inv_nos.collections_id = ? AND inv_nos.inv_no LIKE ? ', [$collID, $inv], 'is'));
            } else {
                array_push($rules, new \PNM\models\RuleExists('inv_nos WHERE inv_nos.inscriptions_id = inscriptions.inscriptions_id AND inv_nos.collections_id =?', $collID, 'i'));
            }
        } elseif (!empty(\PNM\Request::get('title'))) {
            array_push($rules, new \PNM\models\Rule('title', 'exactlike', \PNM\Request::get('title')));
        }
        if (!empty(\PNM\Request::get('object_subtype'))) {
            array_push($rules, new \PNM\models\Rule('object_subtype', 'exact', \PNM\Request::get('object_subtype')));
        } elseif (!empty(\PNM\Request::get('object_type'))) {
            array_push($rules, new \PNM\models\Rule('object_type', 'exact', \PNM\Request::get('object_type')));
        }
        if (!empty(\PNM\Request::get('material'))) {
            array_push($rules, new \PNM\models\Rule('material', 'exact', \PNM\Request::get('material')));
        }
        if (!empty(\PNM\Request::get('text_content'))) {
            array_push($rules, new \PNM\models\Rule('text_content', 'exact', \PNM\Request::get('text_content')));
        }
        if (!empty(\PNM\Request::get('script'))) {
            array_push($rules, new \PNM\models\Rule('script', 'exact', \PNM\Request::get('script')));
        }
        if (!empty(\PNM\Request::get('size'))) {
            if (\PNM\Request::get('size-option') == 'less') {
                array_push($rules, new \PNM\models\Rule('GREATEST(IFNULL(length,0), IFNULL(height,0), IFNULL(width,0), IFNULL(thickness,0))', 'lessorequal', \PNM\Request::get('size'), 'i'));
                array_push($rules, new \PNM\models\Rule('GREATEST(IFNULL(length,0), IFNULL(height,0), IFNULL(width,0), IFNULL(thickness,0))', 'not', 0, 'i'));
            } elseif (\PNM\Request::get('size-option') == 'greater') {
                array_push($rules, new \PNM\models\Rule('GREATEST(IFNULL(length,0), IFNULL(height,0), IFNULL(width,0), IFNULL(thickness,0))', 'moreorequal', \PNM\Request::get('size'), 'i'));
            }
        }
        if (!empty(\PNM\Request::get('place'))) {
            switch (\PNM\Request::get('geo-filter')) {
                case 'origin':
                    array_push($rules, new \PNM\models\Rule('origin', 'exact', \PNM\Request::get('place')));
                    break;
                case 'provenance':
                    array_push($rules, new \PNM\models\Rule('provenance', 'exact', \PNM\Request::get('place')));
                    break;
                case 'installation':
                    array_push($rules, new \PNM\models\Rule('installation_place', 'exact', \PNM\Request::get('place')));
                    break;
                case 'production':
                    array_push($rules, new \PNM\models\Rule('production_place', 'exact', \PNM\Request::get('place')));
                    break;
                case 'all':
                default:
                    array_push($rules, new \PNM\models\Rule(['origin', 'provenance', 'installation_place', 'production_place'], 'exact', \PNM\Request::get('place')));
            }
        }
        if (!empty(\PNM\Request::get('period'))) {
            $periodEnd = \PNM\models\Lookup::dateEnd(\PNM\Request::get('period'));
            $periodStart = \PNM\models\Lookup::dateStart(\PNM\Request::get('period'));
            if (empty($periodStart) || empty($periodEnd)) {
                array_push($rules, new \PNM\models\Rule('0', 'exact', 1, 'i'));
            } else {
                switch (\PNM\Request::get('chrono-filter')) {
                    case 'during':
                        array_push($rules, new \PNM\models\Rule('dating_sort_start', 'lessorequal', $periodEnd, 'i'));
                        array_push($rules, new \PNM\models\Rule('dating_sort_end', 'moreorequal', $periodStart, 'i'));
                        break;
                    case 'not-later':
                        array_push($rules, new \PNM\models\Rule('dating_sort_start', 'lessorequal', $periodEnd, 'i'));
                        break;
                    case 'not-earlier':
                        array_push($rules, new \PNM\models\Rule('dating_sort_end', 'moreorequal', $periodStart, 'i'));
                        break;
                }
            }
        }
        $filter = new \PNM\models\Filter($rules);
        $inscriptions = new \PNM\models\inscriptions(\PNM\Request::get('sort'), (\PNM\Request::get('start') ?: 0), \PNM\Config::ROWS_ON_PAGE, $filter); //$sort = null, $start = 0, $count = 0, Filter $filter = null
        $view = new \PNM\views\inscriptionsView();
        $view->echoRender($inscriptions);
    }
}
