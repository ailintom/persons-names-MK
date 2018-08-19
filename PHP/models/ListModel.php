<?php

/*
 * Description of ListModel
 *
 * This class is used for loading data into tables and bibliography
 */

namespace PNM\models;

class ListModel
{

    const MAX_RECORD_COUNT = 8388607; // Maximum record count defined by the structure of ID fields

    protected $double_params = false;
    public $params = null;
    protected $tablename = null;
    protected $flag = null;
    protected $field_names = null;
    public $defaultsort = null;
    public $start = null;
    public $count = null;
    public $total_count = null;
    protected $distinct = null;
    protected $WHERE = null;
    protected $BWHERE = null;

    protected function initFieldNames()
    {
//to be used in child classes 
    }
    /*
     * does the main thing
     * @param $sort Additional sort order
     * @param $start The position of the first record to return
     * @param $count The number of returned records
     * @param Filter $filter The filter used to select records
     * @param Filter $Bfilter The second filter
     * @param $params Optional parameters 
     * 
     */

    public function __construct($sort = null, $start = 0, $count = 0, Filter $filter = null, Filter $Bfilter = null, $params = null)
    {
        $db = \PNM\Db::getInstance();
        $this->initFieldNames();
        $this->start = $start + 1;
        $this->params = $params;
        if (!empty($filter)) {
            $this->WHERE = (!empty($filter->WHERE) ? ' WHERE ' . $filter->WHERE : null);
        }
        if (!empty($Bfilter)) {
            $this->BWHERE = (!empty($Bfilter->WHERE) ? ' WHERE ' . $Bfilter->WHERE : null);
            $filter = new Filter(array_merge($filter->getRules(), $Bfilter->getRules()));
            // echo $this->BWHERE, "doubleparams:", $this->double_params;
            // print_r($filter);
        }
        $strsql = $this->makeSQL($sort, $start, $count, $filter);
        // echo "<br>$strsql";
        try {
            $stmt = $db->prepare($strsql);
            if (!empty($filter)) {
                $filter->bindParam($stmt, $this->double_params);
            }
            $stmt->execute();
        } catch (\mysqli_sql_exception $e) {
            \PNM\CriticalError::show($e);
        }
        $result = $stmt->get_result();
        $this->count = $result->num_rows;
        $this->data = $result->fetch_all(MYSQLI_ASSOC);
        if ($start == 0 && $count == 0) {
            $this->total_count = $this->count;
        } else {
            $strsqlTotal = 'SELECT FOUND_ROWS()'; //$this->makeSQLTotal();
            // echo "<br>$strsqlTotal";
            // if ($this->defaultsort == 'sequence_number'){ echo "<br>$strsql";} // for testing
            try {
                $stmtTotal = $db->prepare($strsqlTotal);
                /* if (!empty($filter)) {
                  $filter->bindParam($stmtTotal, $this->double_params);
                  } */
                $stmtTotal->execute();
            } catch (\mysqli_sql_exception $e) {
                \PNM\CriticalError::show($e);
            }
            $resultTotal = $stmtTotal->get_result();
            $this->total_count = $resultTotal->fetch_array(MYSQLI_NUM)[0];
        }
        $this->loadChildren();
    }

    protected function loadChildren()
    {
        //to be used in child classes to load child records 
    }

    protected function getSortField($sortField = null)
    {
        if (empty($sortField)) {
            $sortField = $this->defaultsort;
        }
        return $sortField;
    }
    /*
     * compiles the SQL query for a typical SELECT query
     *
     * returns the SQL query
     * @param $inputsort Additional sort order imposed upon the default sort orsed
     * @param $start first record number
     * @param $count number of returned records
     */

    protected function makeSQL($inputsort, $start, $count)
    {
        if (empty($inputsort) || $inputsort == $this->defaultsort) {
            $sort = null;
        } else {
            $sort = $this->getSortField($inputsort);
        }
        if (!empty($sort . $this->getSortField())) {
            $ORDER = ' ORDER BY ' . $sort . (empty($sort) || empty($this->getSortField()) ? null : ', ') . $this->getSortField();
        } else {
            $ORDER = null;
        }
        if ($start > 0 || $count > 0) {
            $LIMIT = ' LIMIT ' . $start . ', ' . ($count > 0 ? $count : self::MAX_RECORD_COUNT);
        } else {
            $LIMIT = null;
        }
        return 'SELECT SQL_CALC_FOUND_ROWS ' . $this->distinct . $this->field_names->SQL() . ' FROM ' . $this->tablename . $this->WHERE . $ORDER . $LIMIT;
    }
    /*
     * returns the field name from field name index
     *
     */

    public function getFieldName($index)
    {
        return $this->field_names->getFieldName($index);
    }
    /*
     * searches for the first row in a multidimensional array where the key $keycolumn has the value $id
     * returns the index if found
     * returns null if nothing found
     */

    protected function rowInArray($id, $keycolumn, $array)
    {
        $total = count($array);
        for ($i = 0; $i < $total; $i++) {
            if ($array[$i][$keycolumn] == $id) {
                return $i;
            }
        }
        return null;
    }
    /*
     * Substitutes the names of the fields used in the ORDER BY clause with the corresponding sort fields (preprocessed in the database for a correct sort order)
     * @param $sortField 
     * @param $oldField Original field(s)
     * @param $newField Corresponding sort field(s)
     */

    protected function replaceSortField($sortField, $oldField, $newField)
    {
        $res = $sortField;
        $oldFieldArr = (array) $oldField;
        $newFieldArr = (array) $newField;
        $total = count($oldFieldArr);
        for ($i = 0; $i < $total; $i++) {
            $matches = [];
            preg_match('/(.+?)\b(.*)/', $res, $matches);
            if ($matches[1] == $oldFieldArr[$i]) {
                $res = $newFieldArr[$i] . $matches[2];
                break;
            }
        }
        return $res;
    }
}
