<?php

/*
 * Description of collectionController
 *
 * This is an EntryController for loading a single collection 
 */

namespace PNM\controllers;

class collectionController extends EntryController
{

    const NAME = 'collection';

    protected function loadChildren()
    {
        $rules = [new \PNM\models\Rule('collections_id', 'exact', $this->record->get('collections_id'), 'i')];
        $filter = new \PNM\models\Filter($rules);
        $obj_inv_nos = new \PNM\models\inv_nos(\PNM\Request::get('sort'), (\PNM\Request::get('start') ?: 0), 50, $filter);
        $this->record->data['inv_nos'] = $obj_inv_nos;
    }
}
