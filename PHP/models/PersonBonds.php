<?php

namespace PNM\models;

/*
 * Descriptin of PersonBonds
 * A model for bonds associated with a particular person
 *
 */

class PersonBonds extends ListModel {

    const BOND_TYPES_SING = ['Mother', 'Father', 'Parent', 'Grandmother', 'Grandfather', 'Grandparent', 'Spouse', 'Sibling', 'Child', 'Grandchild', 'Friend', 'Subordinate person', 'Subordinate to', 'Dependent person', 'Dependent of', 'Other'];
    const BOND_TYPES_PLUR = ['Mothers', 'Fathers', 'Parents', 'Grandmothers', 'Grandfathers', 'Grandparents', 'Spouses', 'Siblings', 'Children', 'Grandchildren', 'Friend', 'Subordinates', 'Subordinate to', 'Dependents', 'Dependent of', 'Others'];

    protected $double_params = true;

    protected function initFieldNames() {
        $this->field_names = new FieldList(['persons_id', 'predicate', 'title_string', 'personal_name', 'gender', 'predic_cat'], ['relative_id', 'predicate', 'title', 'name', 'gender', 'predic_cat']);
    }

    protected function makeSQL($sort, $start, $count, $selectStatement = 'SELECT SQL_CALC_FOUND_ROWS ') {
        //CONCAT("This", predicate ) 1 = This is x of Y 
        $sql1 = 'SELECT (persons_bonds.object_id) as relative_id, predicate, 1 as predic,  title, CONCAT_WS(" ", title_string, personal_name) as name, gender'
                . ' FROM persons_bonds INNER JOIN persons ON persons_bonds.object_id = persons.persons_id'
                . ' WHERE persons_bonds.subject_id=? ORDER BY persons.persons_id';
        //CONCAT( predicate,"This") 0 = Y is x of This
        $sql2 = 'SELECT (persons_bonds.subject_id) as relative_id, predicate, 0 as predic, title, CONCAT_WS(" ", title_string, personal_name) as name, gender'
                . ' FROM persons_bonds INNER JOIN persons ON persons_bonds.subject_id = persons.persons_id'
                . ' WHERE persons_bonds.object_id=? ORDER BY persons.persons_id';
        $sqlres = $selectStatement . " * , case "
                . " WHEN ((predic =  1 and predicate = 'ChildOf') OR (predic = 0 and predicate = 'ParentOf')) AND gender = 'f' then 0 "
                . " WHEN ((predic = 1 and predicate = 'ChildOf') OR (predic = 0 and predicate = 'ParentOf')) AND gender = 'm' then 1"
                . " WHEN ((predic = 1 and predicate = 'ChildOf') OR (predic = 0 and predicate = 'ParentOf')) AND gender <> 'm' AND gender <> 'f' then 2"
                . " WHEN ((predic = 1 and predicate = 'GrandchildOf') OR (predic = 0 and predicate = 'GrandparentOf')) AND gender = 'f' then 3 "
                . " WHEN ((predic = 1 and predicate = 'GrandchildOf') OR (predic = 0 and predicate = 'GrandparentOf')) AND gender = 'm' then 4"
                . " WHEN ((predic = 1 and predicate = 'GrandchildOf') OR (predic = 0 and predicate = 'GrandparentOf')) AND gender <> 'm' AND gender <> 'f' then 5"
                . " WHEN predicate = 'SpouseOf' then 6"
                . " WHEN predicate = 'SiblingOf' then 7"
                . " WHEN ((predic = 0 and predicate = 'ChildOf') OR (predic = 1 and predicate = 'ParentOf')) then 8"
                . " WHEN ((predic = 0 and predicate = 'GrandchildOf') OR (predic = 1 and predicate = 'GrandparentOf' )) then 9"
                . " WHEN predicate = 'FriendshipFor' then 10"
                . " WHEN predic = 0 and predicate = 'SubordinateTo' then 11"
                . " WHEN predic = 1 and predicate = 'SubordinateTo' then 12"
                . " WHEN predic = 0 and predicate = 'DependentOf' then 13"
                . " WHEN predic = 1 and predicate = 'DependentOf' then 14"
                . " ELSE 15 end as predic_cat FROM (($sql1) UNION ($sql2)) as unibonds ORDER BY predic_cat";
        //echo ($sqlres);
        return $sqlres;
    }

}
