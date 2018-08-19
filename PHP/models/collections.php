<?php

/*
 * Description of collections
 * A model representing database records for collections   
 */

namespace PNM\models;

class collections extends ListModel
{

    protected $tablename = 'collections';
    public $defaultsort = 'title';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['collections_id', 'title', 'IFNULL(full_name_en, full_name_national_language)', 'location', 'IFNULL(url, online_collection)', 'IF(online_collection>"", "available", "")', 'tm_coll_id',
            'SELECT COUNT(DISTINCT inscriptions_id) FROM inv_nos WHERE inv_nos.collections_id = collections.collections_id and status<>"erroneous"'], ['collections_id', 'title', 'full_name', 'location', 'url', 'online_collection', 'tm_coll_id',
            'inscriptions_count']);
    }

    protected function getSortField($sortField = null)
    {
        if (empty($sortField)) {
            $sortField = $this->defaultsort;
        }
        return $this->replaceSortField($sortField, ['full_name', 'url',
                    'online_collection'], ['IFNULL(full_name_en, full_name_national_language)', 'IFNULL(url, online_collection)',
                    'IF(online_collection>"", "available", "")']);
    }
}
