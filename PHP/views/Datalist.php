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
 * Description of Datalist
 *
 * @author Tomich
 */
class Datalist {

    private $Db;

    //put your code here
    public function __construct() {
        $this->db = Db::getInstance();
    }

    public function get($name) {


        switch ($name) {
            case "collections":
                $strsql = "SELECT DISTINCT title FROM collections WHERE title >'' ORDER BY title";
                return $this->datalist_from_SQL($strsql, $name);
            case "full-names":
                $strsql = "SELECT DISTINCT IFNULL(full_name_en, full_name_national_language) as full_name FROM collections WHERE full_name_en >'' OR full_name_national_language >'' ORDER BY IFNULL(full_name_en, full_name_national_language)";
                return $this->datalist_from_SQL($strsql, $name);
            case "locations":
                $strsql = "SELECT DISTINCT location FROM collections WHERE location >'' ORDER BY location";
                return $this->datalist_from_SQL($strsql, $name);
            case "periods":
                $strsql = "SELECT item_name FROM thesauri WHERE thesaurus = 5 or thesaurus = 6 ORDER BY item_name";
                return $this->datalist_from_SQL($strsql, $name);
            case "places":
                $strsql = "SELECT place_name FROM places ORDER BY place_name";
                return $this->datalist_from_SQL($strsql, $name);

            case "places":
                $strsql = "SELECT place_name FROM places ORDER BY place_name";
                return $this->datalist_from_SQL($strsql, $name);
            case "name-types-semantic":
                $strsql = "SELECT title FROM name_types_temp INNER JOIN name_types ON name_types_temp.child_id = name_types.name_types_id WHERE name_types_temp.parent_id=251658604 ORDER BY name_types.title;";
                return $this->datalist_from_SQL($strsql, $name);
            case "name-types-formal":
                $strsql = "SELECT title FROM name_types_temp INNER JOIN name_types ON name_types_temp.child_id = name_types.name_types_id WHERE name_types_temp.parent_id=251658605 ORDER BY name_types.title;";
                return $this->datalist_from_SQL($strsql, $name);
            /*
             * $dl->get('name-types-formal');
              echo $dl->get('name-types-semantic');
             * 

             */
            default:
                $html = "<datalist id='$name'>";
                $items = file(dirname(__DIR__) . "/assets/data/$name.txt");


                foreach ($items as $item) {
                    $html .= "<option>" . trim($item) . "</option>";
                }
                $html .= "</datalist>";
                return $html;
        }
    }

    private function datalist_from_SQL($strsql, $name) {
        $html = "<datalist id='$name'><option>";

        try {
            $stmt = $this->db->prepare($strsql);

            $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            CriticalError::Show($e);
        }
        $result = $stmt->get_result();
        $arr = $result->fetch_all(MYSQLI_NUM);

        $html .= implode('</option><option>', array_column($arr, 0));
        $html .= "</option></datalist>";
        return $html;
    }

}
