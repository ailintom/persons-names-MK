<?php

/*
 * Description of bonds
 * A model for bonds between attestations of people on a particular inscribed object 
 *
 */

namespace PNM\models;

class bonds extends ListModel
{

    const BOND_TYPES_SING = ['Mother', 'Father', 'Parent', 'Grandmother', 'Grandfather', 'Grandparent', 'Spouse', 'Sibling', 'Child', 'Grandchild', 'Friend', 'Subordinate person', 'Subordinate to', 'Dependent person', 'Dependent of', 'Other'];
    const BOND_TYPES_PLUR = ['Mothers', 'Fathers', 'Parents', 'Grandmothers', 'Grandfathers', 'Grandparents', 'Spouses', 'Siblings', 'Children', 'Grandchildren', 'Friend', 'Subordinates', 'Subordinate to', 'Dependents', 'Dependent of', 'Others'];

    protected $double_params = true;

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['attestations_id', 'wording', 'predicate',  'title_string', 'personal_name', 'gender', 'predic_cat'], ['relative_id', 'wording', 'predicate',  'title', 'name', 'gender', 'predic_cat']);
    }

    protected function makeSQL($sort, $start, $count, $selectStatement = 'SELECT SQL_CALC_FOUND_ROWS ')
    {
        //CONCAT("This", predicate ) 1 = This is x of Y 
        $sql1 = 'SELECT (bonds.object_id) as relative_id, wording, predicate, 1 as predic,  title_string as title, personal_name as name, gender'
                . ' FROM bonds INNER JOIN attestations ON bonds.object_id = attestations.attestations_id'
                . ' WHERE bonds.subject_id=? ORDER BY attestations.location, attestations.attestations_id';
        //CONCAT( predicate,"This") 0 = Y is x of This
        
        $sql2 = 'SELECT (bonds.subject_id) as relative_id, wording, predicate, 0 as predic, title_string as title, personal_name as name, gender'
                . ' FROM bonds INNER JOIN attestations ON bonds.subject_id = attestations.attestations_id'
                . ' WHERE bonds.object_id=? ORDER BY attestations.location, attestations.attestations_id';
        $sqlres = $selectStatement . " * , case"
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
