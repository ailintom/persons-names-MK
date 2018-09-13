<?php

/*
 * Description of placeController
 * This controller is used to load a single place
 *
 */

namespace PNM\controllers;

use \PNM\Request,
    \PNM\models\Rule,
    \PNM\models\Filter;

class placeController extends EntryController
{

    const NAME = 'place';

    protected function loadChildren()
    {
        $filterFG = new Filter([new Rule('site', 'exact', $this->record->get('place_name'), 's')]);
        $objFG = new \PNM\models\find_groups(Request::get('find_groups_sort'), 0, 0, $filterFG);
        $totalFG = count($objFG->data);
        $this->record->data['count_find_groups'] = $totalFG;
        $this->record->data['find_groups'] = $objFG;
        $filterWk = new Filter([new Rule('production_place', 'exact', $this->record->get('place_name'), 's')]);
        $objWk = new \PNM\models\workshops(Request::get('workshops_sort'), 0, 0, $filterWk);
        $totalWk = count($objWk->data);
        $this->record->data['count_workshops'] = $totalWk;
        $this->record->data['workshops'] = $objWk;
    }
}
