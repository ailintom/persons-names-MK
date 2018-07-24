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

namespace PNM\views;

/**
 * Description of Datalist
 *
 * @author Tomich
 */
class DatalistView
{

    private $db;

    //put your code here
    public function __construct()
    {
        $this->db = \PNM\Db::getInstance();
    }

    public function get($name)
    {
        switch ($name) {
            case "collections":
                $strsql = "SELECT DISTINCT title FROM collections WHERE title >'' ORDER BY title";
                return $this->datalistFromSQL($strsql, $name);
            case "full-names":
                $strsql = "SELECT DISTINCT IFNULL(full_name_en, full_name_national_language) as full_name FROM collections WHERE full_name_en >'' OR full_name_national_language >'' ORDER BY IFNULL(full_name_en, full_name_national_language)";
                return $this->datalistFromSQL($strsql, $name);
            case "locations":
                $strsql = "SELECT DISTINCT location FROM collections WHERE location >'' ORDER BY location";
                return $this->datalistFromSQL($strsql, $name);
            case "periods":
                $strsql = "SELECT item_name FROM thesauri WHERE thesaurus = 5 or thesaurus = 6 ORDER BY item_name";
                return $this->datalistFromSQL($strsql, $name);
            case "materials":
                $strsql = "SELECT item_name FROM thesauri WHERE thesaurus = 3 ORDER BY item_name";
                return $this->datalistFromSQL($strsql, $name);
            case "object-subtypes":
                $strsql = "SELECT item_name FROM thesauri WHERE thesaurus = 2 ORDER BY item_name";
                return $this->datalistFromSQL($strsql, $name);
            case "object-types":
                $strsql = "SELECT item_name FROM thesauri WHERE thesaurus = 1 ORDER BY item_name";
                return $this->datalistFromSQL($strsql, $name);
            case "places":
                $strsql = "SELECT place_name FROM places ORDER BY place_name";
                return $this->datalistFromSQL($strsql, $name);
            case "places":
                $strsql = "SELECT place_name FROM places ORDER BY place_name";
                return $this->datalistFromSQL($strsql, $name);
            case "name-types-semantic":
                $strsql = "SELECT title FROM name_types_temp INNER JOIN name_types ON name_types_temp.child_id = name_types.name_types_id WHERE name_types_temp.parent_id=251658604 ORDER BY name_types.title;";
                return $this->datalistFromSQL($strsql, $name);
            case "name-types-formal":
                $strsql = "SELECT title FROM name_types_temp INNER JOIN name_types ON name_types_temp.child_id = name_types.name_types_id WHERE name_types_temp.parent_id=251658605 ORDER BY name_types.title;";
                return $this->datalistFromSQL($strsql, $name);
            /*
             * $dl->get('name-types-formal');
              echo $dl->get('name-types-semantic');
             * $  $dl->get('object-types'), $dl->get('object-subtypes'), $dl->get('periods'), $dl->get('places')
             *
             */
            default:
                $html = "<datalist id='$name'>";
                $items = file(dirname(__DIR__) . "/assets/data/$name.txt");
                foreach ($items as $item) {
                    $html .= "<option>" . htmlspecialchars(trim($item), ENT_QUOTES, 'UTF-8') . "</option>";
                }
                $html .= "</datalist>";
                return $html;
        }
    }

    private function datalistFromSQL($strsql, $name)
    {
        $html = "\n" . '<datalist id="' . $name . '">';
        try {
            $stmt = $this->db->prepare($strsql);
            $stmt->execute();
        } catch (\mysqli_sql_exception $e) {
            CriticalError::show($e);
        }
        $result = $stmt->get_result();
        $arr = $result->fetch_all(MYSQLI_NUM);
        $html .= implode(array_map([$this, 'singleDatalistEntry'], array_column($arr, 0)));
        $html .= '</datalist>';
        return $html;
    }

    protected function singleDatalistEntry($entry)
    {
        // return "\n" . '<option value="' . htmlspecialchars(trim($entry),ENT_QUOTES,'UTF-8') . '">' . htmlspecialchars(trim($entry),ENT_QUOTES,'UTF-8') . '</option>';
        return "\n" . '<option value="' . htmlspecialchars(trim($entry), ENT_QUOTES, 'UTF-8') . '">';
    }
}
