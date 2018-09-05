<?php

/*
 * Description of typesController
 * This controller is used to load all name types
 *
 */

namespace PNM\controllers;

class typesController
{



    public function load()
    {

        $rules = [new \PNM\models\Rule('name_types_id', 'exact', [\PNM\Config::FORMAL_PATTERNS_ID, \PNM\Config::SEMANTIC_CLASSES_ID], 'i')];
        $filter = new \PNM\models\Filter($rules);
        $model = new \PNM\models\types('name_types_id ASC', 0, 0, $filter);
        (new \PNM\views\typesView())->echoRender($model);
    }
}
