<?php

/*
 * Description of typeController
 * This controller is used to load a single name type
 *
 */

namespace PNM\controllers;

use \PNM\Request,
    \PNM\models\Rule,
    \PNM\models\Filter,
    \PNM\Config;

class typeController extends EntryController
{

    const NAME = 'type';

    public function loadChildren()
    {
        $filterNames = new Filter([new Rule('parent_id', 'exact', $this->record->get('name_types_id'), 'i')]);
        $this->record->data['names'] = new \PNM\models\TypeNames(Request::get('sort'), (Request::get('start') ?: 0), Config::ROWS_ON_PAGE, $filterNames);
        $rules = [new Rule('name_types_id', 'exact', $this->record->get('name_types_id'), 'i')];
        $filter = new Filter($rules);
        $this->record->data['subtypes'] = new \PNM\models\types('name_types_id ASC', 0, 0, $filter);
    }
}
