<?php

namespace PNM\controllers;

use \PNM\Request,
    \PNM\models\Rule,
    \PNM\models\Filter,
    \PNM\Config;

class placesController
{

    const DISTANCE_BETWEEN_NEARBY_PLACES = 30;

    public function load()
    {
        $rules = [];
        if (!empty(Request::get('place'))) {
            array_push($rules, new Rule('place_name', 'exactlike', Request::get('place')));
        }
        if (!empty(Request::get('macroregion'))) {
            if (in_array(Request::get('macroregion'), ["Eastern Desert", "Nile Valley", "Western Desert", "Levant"])) {
                array_push($rules, new Rule('relative_location', 'exact', Request::get('macroregion')));
            } else {
                array_push($rules, new Rule('macro_region', 'exact', Request::get('macroregion')));
            }
        }
        if (!empty(Request::get('northof'))) {
            $northofLat = \PNM\models\Lookup::latitude(Request::get('northof'));
            array_push($rules, new Rule('latitude', 'moreorequal', $northofLat));
        }
        if (!empty(Request::get('southof'))) {
            $southofLat = \PNM\models\Lookup::latitude(Request::get('southof'));
            array_push($rules, new Rule('latitude', 'lessorequal', $southofLat));
        }
        if (!empty(Request::get('near'))) {
            $southofLat = \PNM\models\Lookup::latitude(Request::get('near')) + self::DISTANCE_BETWEEN_NEARBY_PLACES;
            array_push($rules, new Rule('latitude', 'lessorequal', $southofLat));
            $northofLat = \PNM\models\Lookup::latitude(Request::get('near')) - self::DISTANCE_BETWEEN_NEARBY_PLACES;
            array_push($rules, new Rule('latitude', 'moreorequal', $northofLat));
            $relLoc = \PNM\models\Lookup::get('SELECT relative_location FROM places WHERE place_name = ?', Request::get('near'));
            array_push($rules, new Rule('relative_location', 'exact', $relLoc, 's'));
        }
        if (!empty(Request::get('topbib_id'))) {
            array_push($rules, new Rule('topbib_id', 'exact', Request::get('topbib_id'), 's'));
        }
        if (!empty(Request::get('tm_geoid'))) {
            array_push($rules, new Rule('tm_geoid', 'exact', Request::get('tm_geoid'), 'i'));
        }
        $filter = new Filter($rules);
        $model = new \PNM\models\places(Request::get('sort'), (Request::get('start') ?: 0), Config::ROWS_ON_PAGE, $filter);
        $view = new \PNM\views\placesView();
        $view->echoRender($model);
    }
}
