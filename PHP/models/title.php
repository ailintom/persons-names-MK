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
        $this->field_names = new FieldList(['titles_id', 'title', 'CASE 2*EXISTS (SELECT gender FROM titles_att INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id WHERE titles_att.titles_id = titles.titles_id AND gender="f") + EXISTS (SELECT gender FROM titles_att INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id WHERE titles_att.titles_id = titles.titles_id AND gender="m") WHEN 3 THEN "both" WHEN 2 THEN "f" WHEN 1 THEN "m" END', 'SELECT Count(attestations_id) FROM titles_att WHERE titles_att.titles_id=titles.titles_id', 'usage_period', 'usage_area', 'usage_period_note', 'usage_area_note', 'note', 'ward_fischer', 'hannig', 'tla', 'translation_en', 'translation_de'], ['titles_id', 'title', 'gender', 'count_attestations', 'usage_period', 'usage_area', 'usage_period_note', 'usage_area_note', 'note', 'ward_fischer', 'hannig', 'tla', 'translation_en', 'translation_de']);
    }

    protected function parse()
    {
        //This should be implemented in child classes to parse data after retrieving from the database
        $this->parseNote(['usage_period_note', 'usage_area_note', 'note']);
        //collections.collections_id', 'title', 'inv_no', 'status'], ['collections_id', 'title', 'inv_no', 'status']);
    }
}
