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
        $this->field_names = new FieldList(['attestations_id', 'wording', 'predicate', 'predic', 'title_string', 'personal_name', 'gender', 'predic_cat'], ['relative_id', 'wording', 'predicate', 'predic', 'title', 'name', 'gender', 'predic_cat']);
    }

    protected function makeSQL($sort, $start, $count)
    {
        $sql1 = 'SELECT (bonds.object_id) as relative_id, wording, predicate, CONCAT("This", predicate ) as predic,  title_string as title, personal_name as name, gender'
                . ' FROM bonds INNER JOIN attestations ON bonds.object_id = attestations.attestations_id'
                . ' WHERE bonds.subject_id=? ORDER BY attestations.location, attestations.attestations_id';
        $sql2 = 'SELECT (bonds.subject_id) as relative_id, wording, predicate, CONCAT( predicate,"This") as predic, title_string as title, personal_name as name, gender'
                . ' FROM bonds INNER JOIN attestations ON bonds.subject_id = attestations.attestations_id'
                . ' WHERE bonds.object_id=? ORDER BY attestations.location, attestations.attestations_id';
        $sqlres = "SELECT SQL_CALC_FOUND_ROWS * , case WHEN (predic = 'ThisChildOf' OR predic = 'ThisGenericChildOf' OR predic = 'ParentOfThis') AND gender = 'f' then 0 "
                . " WHEN (predic = 'ThisChildOf' OR predic = 'ThisGenericChildOf' OR predic = 'ParentOfThis') AND gender = 'm' then 1"
                . " WHEN (predic = 'ThisChildOf' OR predic = 'ThisGenericChildOf' OR predic = 'ParentOfThis') AND gender <> 'm' AND gender <> 'f' then 2"
                . " WHEN (predic = 'ThisGrandchildOf' OR predic = 'GrandparentOfThis') AND gender = 'f' then 3 "
                . " WHEN (predic = 'ThisGrandchildOf' OR predic = 'GrandparentOfThis') AND gender = 'm' then 4"
                . " WHEN (predic = 'ThisGrandchildOf' OR predic = 'GrandparentOfThis') AND gender <> 'm' AND gender <> 'f' then 5"
                . " WHEN predicate = 'SpouseOf' then 6"
                . " WHEN predicate = 'SiblingOf' then 7"
                . " WHEN (predic = 'GenericChildOfThis' OR predic = 'ChildOfThis' OR predic = 'ThisParentOf') then 8"
                . " WHEN (predic = 'GrandchildOfThis' OR predic = 'ThisGrandparentOf' ) then 9"
                . " WHEN predicate = 'FriendshipFor' then 10"
                . " WHEN predic = 'SubordinateToThis' then 11"
                . " WHEN predic = 'ThisSubordinateTo' then 12"
                . " WHEN predic = 'DependentOfThis' then 13"
                . " WHEN predic = 'ThisDependentOf' then 14"
                . " ELSE 15 end as predic_cat FROM (($sql1) UNION ($sql2)) as unibonds ORDER BY predic_cat";
        //echo ($sqlres);
        return $sqlres;
    }
}
