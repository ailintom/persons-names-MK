<?php

/*
 * Description of EntryBibliography
 * This class is used to load bibliography for a particular object
 *
 */

namespace PNM\models;

class EntryBibliography extends ListModel
{

    protected $tablename = 'biblio_refs LEFT JOIN publications ON biblio_refs.source_id = publications.publications_id';
    public $defaultsort = 'order_value, year ASC, source_url, source_title, author_year_sort';

    //(source_id>0) DESC , source_url, source_title, author_year_sort
    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['author_year', 'publications_id', 'source_id', 'source_url', 'source_title', 'pages', 'DATE_FORMAT(accessed_on, "%M %e, %Y")', 'reference_type'], ['author_year', 'publications_id', 'source_id', 'source_url', 'source_title', 'pages', 'accessed_on', 'reference_type']);
    }
}
