<?php

namespace PNM\controllers;

class placesController
{

    public function load()
    {
        $rules = [];
        if (!empty(\PNM\Request::get('place'))) {
            array_push($rules, new \PNM\models\Rule('place_name', 'exactlike', \PNM\Request::get('place')));
        }
        if (!empty(\PNM\Request::get('macroregion'))) {
            if (in_array(\PNM\Request::get('macroregion'), ["Eastern Desert", "Nile Valley", "Western Desert", "Levant"])) {
                array_push($rules, new \PNM\models\Rule('relative_location', 'exact', \PNM\Request::get('macroregion')));
            } else {
                array_push($rules, new \PNM\models\Rule('macro_region', 'exact', \PNM\Request::get('macroregion')));
            }
        }
        if (!empty(\PNM\Request::get('northof'))) {
            $northofLat = \PNM\models\Lookup::latitude(\PNM\Request::get('northof'));
            array_push($rules, new \PNM\models\Rule('latitude', 'moreorequal', $northofLat));
        }
        if (!empty(\PNM\Request::get('southof'))) {
            $southofLat = \PNM\models\Lookup::latitude(\PNM\Request::get('southof'));
            array_push($rules, new \PNM\models\Rule('latitude', 'lessorequal', $southofLat));
        }
        if (!empty(\PNM\Request::get('near'))) {
            $southofLat = \PNM\models\Lookup::latitude(\PNM\Request::get('near')) + 30;
            array_push($rules, new \PNM\models\Rule('latitude', 'lessorequal', $southofLat));
            $northofLat = \PNM\models\Lookup::latitude(\PNM\Request::get('near')) - 30;
            array_push($rules, new \PNM\models\Rule('latitude', 'moreorequal', $northofLat));
        }
        if (!empty(\PNM\Request::get('topbib_id'))) {
            array_push($rules, new \PNM\models\Rule('topbib_id', 'exact', \PNM\Request::get('topbib_id'), 's'));
        }
        if (!empty(\PNM\Request::get('tm_geoid'))) {
            array_push($rules, new \PNM\models\Rule('tm_geoid', 'exact', \PNM\Request::get('tm_geoid'), 'i'));
        }
        $filter = new \PNM\models\Filter($rules);
        $model = new \PNM\models\places(\PNM\Request::get('sort'), (\PNM\Request::get('start') ?: 0), 50, $filter);
        $view = new \PNM\views\placesView();
        $view->echoRender($model);
    }
}
