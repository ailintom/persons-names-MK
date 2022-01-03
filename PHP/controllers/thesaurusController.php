<?php

namespace PNM\controllers;

/* Description of thesaurusController
 * Controls requests for a single thesaurus item */


use \PNM\Request,
    \PNM\models\Rule,
    \PNM\models\Lookup,
    \PNM\models\Filter,
    \PNM\Config;    

class thesaurusController extends EntryController
{

    const NAME = 'thesaurus';
        public function load()
    {
        $requestedID =     Request::get('id');
        if (is_numeric ($requestedID)){
            $id = $requestedID;
        }else{
            $id = Lookup::getThesaurusID($requestedID);
        }
        $this->loadID((int) $id);
    }
        protected function loadChildren()
    {
        $rules = [new Rule('parent', 'exact', $this->record->get('thesauri_id'), 'i')];
        $filter = new Filter($rules);
        $obj_nested = new \PNM\models\thesauri(Request::get('sort'), (Request::get('start') ?: 0), Config::ROWS_ON_PAGE, $filter);
        $this->record->data['nested'] = $obj_nested;
        $totalNested= count($obj_nested->data);
        $this->record->data['count_nested'] = $totalNested;
        $rules_items = [new Rule('thesaurus', 'exact', $this->record->get('thesauri_id'), 'i')];
        $filter_items = new Filter($rules_items);
        $obj_items = new \PNM\models\thesauri(Request::get('sort'), 0, 1000, $filter_items, null, null, true);
        $this->record->data['items'] = $obj_items;
        $totalItems= count($obj_items->data);
        $this->record->data['count_items'] = $totalItems;
    }
}
