<?php

/*
 * Description of workshopController
 * This controller is used to load a single workshop
 *
 */

namespace PNM\controllers;

class workshopController extends EntryController
{

    const NAME = 'workshop';

    protected function loadChildren()
    {
        $filter = new \PNM\models\Filter([new \PNM\models\Rule('workshops_id', 'exact', $this->record->getID(), 'i')]);
        $objIns = new \PNM\models\WorkshopInscriptions(\PNM\Request::get('sort'), 0, 0, $filter);
        $this->record->data['inscriptions'] = $objIns;
    }
}
