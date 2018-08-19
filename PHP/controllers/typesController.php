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
        $rules = [new \PNM\models\Rule('name_types_id', 'exact', [251658605, 251658604], 'i')];
        $filter = new \PNM\models\Filter($rules);
        $model = new \PNM\models\types('name_types_id ASC', 0, 0, $filter);
        (new \PNM\views\typesView())->echoRender($model);
    }
}
