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
 * Description of ListModel
 *
 * This class is used for loading data into tables and bibliography
 */
class ListModel {

    const MAX_RECORD_COUNT = 8388607; // Maximum record count defined by the structure of ID fields

    public $data = null;
    protected $tablename = null;
    protected $field_names = null;
    public $defaultsort = null;
    public $start = null;
    public $count = null;
    public $total_count = null;

    protected function initFieldNames() {
        
    }

    public function __construct($sort = null, $start = 0, $count = 0, Filter $filter = null) {
        $db = Db::getInstance();
        $this->initFieldNames();
        $filterTotal = $filter;
        $this->start = $start + 1;

        $strsql = $this->makeSQL($sort, $start, $count, $filter);
        //  echo "<br>$strsql"; //TURN ON TO DISPLAY THE PRINCIPAL QUERY
        // if ($this->defaultsort == 'sequence_number'){ echo "<br>$strsql";} // for testing
        try {
            $stmt = $db->prepare($strsql);
            if (!empty($filter)) {
                $filter->bind_param($stmt);
            }
            $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            CriticalError::Show($e);
        }
        $result = $stmt->get_result();
        $this->count = $result->num_rows;
        $this->data = $result->fetch_all(MYSQLI_ASSOC);
        if ($start == 0 && $count == 0) {
            $this->total_count = $this->count;
        } else {
            $strsqlTotal = $this->makeSQLTotal($filterTotal);
            //echo "<br>$strsqlTotal";
            // if ($this->defaultsort == 'sequence_number'){ echo "<br>$strsql";} // for testing
            try {
                $stmtTotal = $db->prepare($strsqlTotal);
                if (!empty($filter)) {
                    $filter->bind_param($stmtTotal);
                }
                $stmtTotal->execute();
            } catch (mysqli_sql_exception $e) {
                CriticalError::Show($e);
            }
            $resultTotal = $stmtTotal->get_result();
            $this->total_count = $resultTotal->fetch_array(MYSQLI_NUM)[0];
            
        }
        $this->load_children();
    }

    protected function load_children() {
        
    }

    protected function getSortField($sortField = NULL) {
        if (empty($sortField)) {
            $sortField = $this->defaultsort;
        }
        return $sortField;
    }

    /*
     * compiles the SQL query for a typical SELECT query
     * 
     */

    protected function makeSQL($inputsort, $start, $count, Filter $filter = null) {
        $WHERE = (!empty($filter->WHERE) ? ' WHERE ' . $filter->WHERE : null);
        if ($inputsort == $this->defaultsort) {
            $sort = NULL;
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
        return 'SELECT ' . $this->field_names->SQL() . ' FROM ' . $this->tablename . $WHERE . $ORDER . $LIMIT;
    }

    protected function makeSQLTotal(Filter $filter = null) {
        $WHERE = (!empty($filter->WHERE) ? ' WHERE ' . $filter->WHERE : null);
        //echo $WHERE;
        return 'SELECT Count(*) as count FROM ' . $this->tablename . $WHERE;
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
     * returns NULL if nothing found
     */

    protected function rowInArray($id, $keycolumn, $array) {

        $total = count($array);
        for ($i = 0; $i < $total; $i++) {
            if ($array[$i][$keycolumn] == $id) {
                return $i;
            }
        }
        return NULL;
    }

    protected function replaceSortField($sortField, $oldField, $newField) {
        $res = $sortField;
        $oldFieldArr = (array) $oldField;
        $newFieldArr = (array) $newField;
        $total = count($oldFieldArr);
        for ($i = 0; $i < $total; $i++) {
            $len = strlen($oldFieldArr[$i]);
            if (substr($res, 0, $len) == $oldFieldArr[$i]) {
                $res = $newFieldArr[$i] . substr($res, $len);
                break;
            }
        }
        return $res;
    }

}
