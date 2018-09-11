<?php

/*
 * Description of Datalist
 * This class is used to render the HTML code for a datalist control loading the list of entries from the database
 *
 */

namespace PNM\views;

use PNM\Config;

class Datalist
{

    private $db;

    public function __construct()
    {
        $this->db = \PNM\Db::getInstance();
    }

    public function get($name)
    {
        switch ($name) {
            case "collections":
                $strsql = "SELECT DISTINCT title FROM collections WHERE title >'' ORDER BY title";
                return $this->datalistFromSQL($name, $strsql, null, null);
            case "full-names":
                $strsql = "SELECT DISTINCT IFNULL(full_name_en, full_name_national_language) as full_name FROM collections WHERE full_name_en >'' OR full_name_national_language >'' ORDER BY IFNULL(full_name_en, full_name_national_language)";
                return $this->datalistFromSQL($name, $strsql, null, null);
            case "locations":
                $strsql = "SELECT DISTINCT location FROM collections WHERE location >'' ORDER BY location";
                return $this->datalistFromSQL($name, $strsql, null, null);

            case "materials":
                $strsql = "SELECT item_name FROM thesauri WHERE thesaurus = ? ORDER BY item_name";
                $value = 3;
            case "object-subtypes":
                $strsql = "SELECT item_name FROM thesauri WHERE thesaurus = ? ORDER BY item_name";
                $value = 2;
            case "object-types":
                $strsql = "SELECT item_name FROM thesauri WHERE thesaurus = ? ORDER BY item_name";
                $value = 1;
            case "name-types-semantic":
                $strsql = "SELECT title FROM name_types_temp INNER JOIN name_types ON name_types_temp.child_id = name_types.name_types_id WHERE name_types_temp.parent_id=? ORDER BY name_types.title;";
                $value = Config::SEMANTIC_CLASSES_ID;
            case "name-types-formal":
                $strsql = "SELECT title FROM name_types_temp INNER JOIN name_types ON name_types_temp.child_id = name_types.name_types_id WHERE name_types_temp.parent_id=? ORDER BY name_types.title;";
                $value = Config::FORMAL_PATTERNS_ID;
            case "places":
                $strsql = "SELECT place_name FROM places ORDER BY place_name";
                return $this->datalistFromSQL($name, $strsql, null, null);
            case "periods":
            default:
                $strsql = "SELECT item_name FROM thesauri WHERE thesaurus = 5 OR thesaurus = ? ORDER BY item_name";
                $value = 6;
        }
        return $this->datalistFromSQL($name, $strsql, $value);
    }

    private function datalistFromSQL($name, $strsql, $value, $param = 'i')
    {
        $html = "\n" . '<datalist id="' . $name . '">';
        $arr = \PNM\models\Lookup::getColumn($strsql, $value, $param);
        $html .= implode(array_map([$this, 'singleDatalistEntry'], array_column($arr, 0)));
        $html .= '</datalist>';
        return $html;
    }

    protected function singleDatalistEntry($entry)
    {

        return "\n" . '<option value="' . htmlspecialchars(trim($entry), ENT_QUOTES, 'UTF-8') . '">';
    }
}
