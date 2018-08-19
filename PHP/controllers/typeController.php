<?php

/*
 * Description of typeController
 * This controller is used to load a single name type
 *
 */

namespace PNM\controllers;

class typeController extends EntryController
{

    const NAME = 'type';

    public function loadChildren()
    {
        $filterNames = new \PNM\models\Filter([new \PNM\models\Rule('parent_id', 'exact', $this->record->get('name_types_id'), 'i')]);
        $this->record->data['names'] = new \PNM\models\TypeNames(\PNM\Request::get('sort'), (\PNM\Request::get('start') ?: 0), 50, $filterNames);
        $rules = [new \PNM\models\Rule('name_types_id', 'exact', $this->record->get('name_types_id'), 'i')];
        $filter = new \PNM\models\Filter($rules);
        $this->record->data['subtypes'] = new \PNM\models\types('name_types_id ASC', 0, 0, $filter);
    }
}
