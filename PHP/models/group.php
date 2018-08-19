<?php

/*
 * Description of group
 * A model for a single fing group
 */

namespace PNM\models;

class group extends EntryModel
{

    protected $tablename = 'find_groups';
    protected $hasBiblio = true;
    protected $idField = 'find_groups_id';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['find_groups_id', 'site', 'site_area', 'exact_location', 'title', 'find_group_type', 'architecture', 'human_remains',
            'finds', 'disturbance', 'dating', 'dating_note', 'note']);
    }

    protected function parse()
    {
        $this->parseNote(['dating_note', 'note']);
    }

    protected function loadChildren()
    {
        $filter = new Filter([new Rule('find_groups_id', 'exact', $this->getID(), 'i')]);
        $objIns = new inscriptions(null, 0, 0, $filter);
        $this->data['inscriptions'] = $objIns;
    }
}
