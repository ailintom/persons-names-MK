<?php

/*
 * Description of collection
 * A model for a single collection
 */

namespace PNM\models;

class collection extends EntryModel
{

    protected $tablename = 'collections';
    protected $hasBiblio = false;
    protected $idField = 'collections_id';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['collections_id', 'title', 'full_name_en', 'full_name_national_language', 'location', 'url',
            'online_collection', 'tm_coll_id', 'SELECT COUNT(DISTINCT objects_id) FROM inv_nos '
            . 'WHERE inv_nos.collections_id = collections.collections_id and `status`<>"erroneous"', 'thot_concept_id',
            'artefacts_url'], ['collections_id', 'title', 'full_name_en', 'full_name_national_language', 'location', 'url',
            'online_collection', 'tm_coll_id', 'inscriptions_count', 'thot_concept_id',
            'artefacts_url']);
    }

    protected function loadChildren()
    {
        $filter = new Filter([new Rule('collections_id', 'exact', $this->getID(), 'i')]);
        $objIns = new inv_nos(null, 0, 0, $filter);
        $this->data['inv_nos'] = $objIns;
    }
}
