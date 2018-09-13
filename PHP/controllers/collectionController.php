<?php

/*
 * Description of collectionController
 *
 * This is an EntryController for loading a single collection 
 */

namespace PNM\controllers;

use \PNM\Request,
    \PNM\models\Rule,
    \PNM\models\RuleExists,
    \PNM\models\Filter,
    \PNM\Config;

class collectionController extends EntryController
{

    const NAME = 'collection';

    protected function loadChildren()
    {
        $rules = [new Rule('collections_id', 'exact', $this->record->get('collections_id'), 'i')];
        $filter = new Filter($rules);
        $obj_inv_nos = new \PNM\models\inv_nos(Request::get('sort'), (Request::get('start') ?: 0), Config::ROWS_ON_PAGE, $filter);
        $this->record->data['inv_nos'] = $obj_inv_nos;
    }
}
