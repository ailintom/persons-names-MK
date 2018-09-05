<?php

/*
 * Description of collectionController
 *
 * This is a controller for searching collections
 */

namespace PNM\controllers;

class collectionsController
{

    public function load()
    {
        $rules = [];
        if (!empty(\PNM\Request::get('title'))) {
            array_push($rules, new \PNM\models\Rule('title', 'exactlike', \PNM\Request::get('title')));
        }
        if (!empty(\PNM\Request::get('full_name'))) {
            array_push($rules, new \PNM\models\Rule(['full_name_en', 'full_name_national_language'], 'exactlike', \PNM\Request::get('full_name')));
        }
        if (!empty(\PNM\Request::get('location'))) {
            array_push($rules, new \PNM\models\Rule('location', 'exactlike', \PNM\Request::get('location')));
        }
        if (!empty(\PNM\Request::get('tm_coll_id'))) {
            array_push($rules, new \PNM\models\Rule('tm_coll_id', 'exact', \PNM\Request::get('tm_coll_id')));
        }
        $filter = new \PNM\models\Filter($rules);

        $model = new \PNM\models\collections(\PNM\Request::get('sort'), (\PNM\Request::get('start') ?: 0), \PNM\Config::ROWS_ON_PAGE, $filter);
        $view = new \PNM\views\collectionsView();
        $view->echoRender($model);
    }
}
