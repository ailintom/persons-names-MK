<?php

/*
 * MIT License
 *
 * Copyright (c) 2017 Alexander Ilin-Tomich (unless specified otherwise for individual source files and documents)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace PNM;

/**
 *
 *
 */
class PersonBonds extends ListModel
{

    const BOND_TYPES_SING = ['Mother', 'Father', 'Parent', 'Grandmother', 'Grandfather', 'Grandparent', 'Spouse', 'Sibling', 'Child', 'Grandchild', 'Friend', 'Subordinate person', 'Subordinate to', 'Dependent person', 'Dependent of', 'Other'];
    const BOND_TYPES_PLUR = ['Mothers', 'Fathers', 'Parents', 'Grandmothers', 'Grandfathers', 'Grandparents', 'Spouses', 'Siblings', 'Children', 'Grandchildren', 'Friend', 'Subordinates', 'Subordinate to', 'Dependents', 'Dependent of', 'Others'];

    protected $double_params = true;

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['persons_id', 'predicate', 'predic', 'title_string', 'personal_name', 'gender', 'predic_cat'], ['relative_id', 'predicate', 'predic', 'title', 'name', 'gender', 'predic_cat']);
    }

    protected function makeSQL($sort, $start, $count)
    {
        $sql1 = 'SELECT (persons_bonds.object_id) as relative_id, predicate, CONCAT("This", predicate ) as predic,  title, CONCAT_WS(" ", title_string, personal_name) as name, gender'
                . ' FROM persons_bonds INNER JOIN persons ON persons_bonds.object_id = persons.persons_id'
                . ' WHERE persons_bonds.subject_id=? ORDER BY persons.persons_id';
        $sql2 = 'SELECT (persons_bonds.subject_id) as relative_id, predicate, CONCAT( predicate,"This") as predic, title, CONCAT_WS(" ", title_string, personal_name) as name, gender'
                . ' FROM persons_bonds INNER JOIN persons ON persons_bonds.subject_id = persons.persons_id'
                . ' WHERE persons_bonds.object_id=? ORDER BY persons.persons_id';
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