<?php

/*
 * Description of workshopController
 * This controller is used to load a single workshop
 *
 */

namespace PNM\controllers;

use \PNM\Request,
    \PNM\models\Rule,
    \PNM\models\Filter;

class workshopController extends EntryController
{

    const NAME = 'workshop';

    protected function loadChildren()
    {
        $filter = new Filter([new Rule('workshops_id', 'exact', $this->record->getID(), 'i')]);
        $objIns = new \PNM\models\WorkshopInscriptions(Request::get('sort'), 0, 0, $filter);
        $this->record->data['inscriptions'] = $objIns;
    }
}
