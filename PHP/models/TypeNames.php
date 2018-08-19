<?php

namespace PNM\models;

/*
 * Description of TypeNames
 * A model for personal names belonging to a particular name type
 */

class TypeNames extends names
{

    protected $distinct = ' DISTINCT ';
    protected $tablename = 'personal_names INNER JOIN (names_types_xref INNER JOIN name_types_temp  ON names_types_xref.name_types_id = name_types_temp.child_id) ON personal_names.personal_names_id = names_types_xref.personal_names_id';
}
