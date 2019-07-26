<?php

namespace PNM\controllers;

/* Description of thesaurusController
 * Controls requests for a single thesaurus item */


use \PNM\Request,
    \PNM\models\Lookup;    

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
}
