<?php

/*
 * Description of Datalist
 * This class is used to render the HTML code for a datalist control loading the list of entries from the database
 *
 */

namespace PNM\views;

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
                $strsql = "SELECT title FROM name_types_temp INNER JOIN name_types ON name_types_temp.child_id = name_types.name_types_id WHERE name_types_temp.parent_id=" . \PNM\Config::SEMANTIC_CLASSES_ID . " ORDER BY name_types.title;";
                return $this->datalistFromSQL($strsql, $name);
            case "name-types-formal":
                $strsql = "SELECT title FROM name_types_temp INNER JOIN name_types ON name_types_temp.child_id = name_types.name_types_id WHERE name_types_temp.parent_id=" . \PNM\Config::FORMAL_PATTERNS_ID . " ORDER BY name_types.title;";
                return $this->datalistFromSQL($strsql, $name);
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

        return "\n" . '<option value="' . htmlspecialchars(trim($entry), ENT_QUOTES, 'UTF-8') . '">';
    }
}
