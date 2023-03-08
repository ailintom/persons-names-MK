<?php

/*
 * Description of ListModel
 *
 * This class is used for loading data into tables and bibliography
 */

namespace PNM\models;

class ListModel {

    const MAX_RECORD_COUNT = 8388607; // Maximum record count defined by the structure of ID fields

    public $data; // the data retrieved by the model
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

    protected function initFieldNames() {
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

    public function __construct($sort = null, $start = 0, $count = 0, Filter $filter = null, Filter $Bfilter = null, $params = null, $noTotalCount = false) {
        $db = \PNM\Db::getInstance();
        $this->initFieldNames();
        $this->start = $start + 1;
        $this->params = $params;
        if (!empty($filter)) {
            $this->WHERE = (!empty($filter->WHERE) ? ' WHERE ' . $filter->WHERE : null);
        }
        if (!empty($filter) && !empty($Bfilter)) {
            $this->BWHERE = (!empty($Bfilter->WHERE) ? ' WHERE ' . $Bfilter->WHERE : null);
            $filter = new Filter(array_merge($filter->getRules(), $Bfilter->getRules()));
            // echo $this->BWHERE, "doubleparams:", $this->double_params;
            // print_r($filter);
        }
        if ($noTotalCount) {
            $selectStatement = 'SELECT ';
        } else {
            $selectStatement = 'SELECT SQL_CALC_FOUND_ROWS ';
        }
        $strsql = $this->makeSQL($sort, $start, $count, $selectStatement);

        try {
            $stmt = $db->prepare($strsql);
            if (!empty($filter)) {
                $filter->bindParam($stmt, $this->double_params);
            }
            // $startTime = microtime(true);
            $stmt->execute();
            /*
            $duration = microtime(true) - $startTime;
            if ($duration < 0.0002) {
                $durDescr = "negligible";
            } elseif ($duration < 0.0008) {
                $durDescr = "fast";
            } elseif ($duration < 0.002) {
                $durDescr = "light";
            } elseif ($duration < 0.02) {
                $durDescr = "heavyish";
            } elseif ($duration < 0.08) {
                $durDescr = "heavy";
            } elseif ($duration < 0.2) {
                $durDescr = "massive";
            } else {
                $durDescr = "slow";
            }
            echo "<br> $duration s $durDescr $strsql<br>";
             * 
             */
            $result = $stmt->get_result();
            $this->count = $result->num_rows;
            $this->data = $result->fetch_all(MYSQLI_ASSOC);
        } catch (\mysqli_sql_exception $e) {
            \PNM\CriticalError::show($e);
        }
        if ($noTotalCount) {
            $this->total_count = 0;
        } elseif ($start == 0 && $count == 0) {
            $this->total_count = $this->count;
        } else {
            $strsqlTotal = 'SELECT FOUND_ROWS()';

            try {
                $stmtTotal = $db->prepare($strsqlTotal);

                $stmtTotal->execute();
                $resultTotal = $stmtTotal->get_result();
                $this->total_count = $resultTotal->fetch_array(MYSQLI_NUM)[0];
            } catch (\mysqli_sql_exception $e) {
                \PNM\CriticalError::show($e);
            }
        }

        $this->loadChildren();
    }

    protected function loadChildren() {
        //to be used in child classes to load child records 
    }

    protected function getSortField($sortField = null) {
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

    protected function makeSQL($inputsort, $start, $count, $selectStatement = 'SELECT SQL_CALC_FOUND_ROWS ') {
        if (empty($inputsort) || $inputsort == $this->defaultsort) {
            $sort = null;
        } else {
            $sort = $this->getSortField($inputsort);
            /*
               if (!((0 === substr_compare($sort, " DESC", -5)) or (0 === substr_compare($sort, " ASC", -4)))){
                $sort = null;
            }else{
                $sortr = trim(substr($sort, 0, -4));
                if (preg_match('/[^a-zA-Z_]/', $sortr)){
                    $sort = null;
                }
            }
             * 
             */
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

        return $selectStatement . $this->distinct . $this->field_names->SQL() . ' FROM ' . $this->tablename . $this->WHERE . $ORDER . $LIMIT;
    }

    /*
     * returns the field name from field name index
     *
     */

    public function getFieldName($index) {
        return $this->field_names->getFieldName($index);
    }

    /*
     * searches for the first row in a multidimensional array where the key $keycolumn has the value $id
     * returns the index if found
     * returns null if nothing found
     */

    protected function rowInArray($id, $keycolumn, $array) {
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

    protected function replaceSortField($sortField, $oldField, $newField) {
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
