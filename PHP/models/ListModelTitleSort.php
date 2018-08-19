<?php

namespace PNM\models;

/*
 * Description of ListModelTitleSort
 * 
 * Represents a ListModel with the default sort order using the field title_sort
 *
 */

class ListModelTitleSort extends ListModel
{

    protected function getSortField($sortField = null)
    {
        if (empty($sortField)) {
            $sortField = $this->defaultsort;
        }
        return $this->replaceSortField($sortField, 'title', 'title_sort');
    }
}
