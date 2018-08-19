<?php

/*
 * Description of title_relations
 * A model for relations of a particular title with other titles
 *
 */

namespace PNM\models;

class title_relations extends ListModel
{

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['titles_id', 'title', 'predicate']);
    }

    protected function makeSQL($sort, $start, $count)
    {
        $sql1 = 'SELECT (object_id) as titles_id, title, predicate, title_sort, 0 as pred_sort'
                . ' FROM title_relations INNER JOIN titles ON title_relations.object_id = titles.titles_id'
                . ' WHERE subject_id=? ';
        $sql2 = 'SELECT (subject_id) as titles_id, title, CASE predicate WHEN "specificates" THEN "specificated in" '
                . 'WHEN "refers to" THEN "referred to in" ELSE predicate END  as predicate, title_sort, CASE predicate WHEN "specificates" THEN 1 '
                . 'WHEN "refers to" THEN 1 ELSE 0 END as pred_sort'
                . ' FROM title_relations INNER JOIN titles ON title_relations.subject_id = titles.titles_id'
                . ' WHERE object_id=? ';
        $sqlres = "SELECT SQL_CALC_FOUND_ROWS * FROM (($sql1) UNION ($sql2)) as unibonds ORDER BY pred_sort, predicate, title_sort";
        //echo ($sqlres);
        return $sqlres;
    }
}
