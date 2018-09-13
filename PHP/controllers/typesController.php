<?php

/*
 * Description of typesController
 * This controller is used to load all name types
 *
 */

namespace PNM\controllers;

use \PNM\models\Rule,
    \PNM\models\Filter,
    \PNM\Config;

class typesController
{

    public function load()
    {

        $rules = [new Rule('name_types_id', 'exact', [Config::FORMAL_PATTERNS_ID, Config::SEMANTIC_CLASSES_ID], 'i')];
        $filter = new Filter($rules);
        $model = new \PNM\models\types('name_types_id ASC', 0, 0, $filter);
        (new \PNM\views\typesView())->echoRender($model);
    }
}
