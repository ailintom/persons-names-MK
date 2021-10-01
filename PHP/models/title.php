<?php

/*
 * Description of title
 * A model for a single title
 *
 */

namespace PNM\models;

class title extends EntryModel
{

    protected $tablename = 'titles';
    protected $hasBiblio = true;
    protected $idField = 'titles_id';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['titles_id', 'title', 'gender', 'SELECT Count(attestations_id) FROM titles_att WHERE titles_att.titles_id=titles.titles_id', 'usage_period', 'usage_area', 'usage_period_note', 'usage_area_note', 'note', 'ward_fischer', 'hannig', 'taylor', 'ayedi', 'tla', 'translation_en', 'translation_de'], ['titles_id', 'title', 'gender', 'count_attestations', 'usage_period', 'usage_area', 'usage_period_note', 'usage_area_note', 'note', 'ward_fischer', 'hannig', 'taylor', 'ayedi', 'tla', 'translation_en', 'translation_de']);
    }

    protected function parse()
    {
        //This should be implemented in child classes to parse data after retrieving from the database
        $this->parseNote(['usage_period_note', 'usage_area_note', 'note']);
        //collections.collections_id', 'title', 'inv_no', 'status'], ['collections_id', 'title', 'inv_no', 'status']);
    }
}
