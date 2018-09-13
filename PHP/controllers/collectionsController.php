<?php

/*
 * Description of collectionController
 *
 * This is a controller for searching collections
 */

namespace PNM\controllers;

use \PNM\Request,
    \PNM\models\Rule,
    \PNM\models\Filter,
    \PNM\Config;
use \PNM\models\collections,
    \PNM\views\collectionsView;

class collectionsController
{

    public function load()
    {
        $rules = [];
        if (!empty(Request::get('title'))) {
            array_push($rules, new Rule('title', 'exactlike', Request::get('title')));
        }
        if (!empty(Request::get('full_name'))) {
            array_push($rules, new Rule(['full_name_en', 'full_name_national_language'], 'exactlike', Request::get('full_name')));
        }
        if (!empty(Request::get('location'))) {
            array_push($rules, new Rule('location', 'exactlike', Request::get('location')));
        }
        if (!empty(Request::get('tm_coll_id'))) {
            array_push($rules, new Rule('tm_coll_id', 'exact', Request::get('tm_coll_id')));
        }
        $filter = new Filter($rules);

        $model = new collections(Request::get('sort'), (Request::get('start') ?: 0), Config::ROWS_ON_PAGE, $filter);
        $view = new collectionsView();
        $view->echoRender($model);
    }
}
