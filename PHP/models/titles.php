<?php

/*
 * Description of titles
 * This is a model used to represent the records for titles
 */

namespace PNM\models;

class titles extends ListModelTitleSort
{

    protected $tablename = 'titles';
    public $defaultsort = 'title';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['titles_id', 'title', 'CASE 2*EXISTS (SELECT gender FROM titles_att INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id WHERE titles_att.titles_id = titles.titles_id AND gender="f") + EXISTS (SELECT gender FROM titles_att INNER JOIN attestations ON titles_att.attestations_id = attestations.attestations_id WHERE titles_att.titles_id = titles.titles_id AND gender="m") WHEN 3 THEN "both" WHEN 2 THEN "f" WHEN 1 THEN "m" END', 'SELECT Count(attestations_id) FROM titles_att WHERE titles_att.titles_id=titles.titles_id', 'usage_period', 'usage_area', 'ward_fischer', 'hannig', 'translation_en'], ['titles_id', 'title', 'gender', 'count_attestations', 'usage_period', 'usage_area', 'ward_fischer', 'hannig', 'translation_en']);
    }
}
