<?php

namespace PNM\models;

/*
 * Description of Lookup
 * 
 * A static class used to perform simple queries to the database
 *
 */

class Lookup
{

    const TEXT_CONTENT_THESAURUS = 4;
    const SCRIPT_THESAURUS = 12;
    const OBJECT_TYPES_THESAURUS = 1;
    const RETURN_VAL = 0; //return a single value
    const RETURN_ASSOC = 1; //return an associated array representing the results
    const RETURN_INDEXED = 2;  //return an indexed array
    /*
     * the main function used to retrieve results and return them as an array or a single value
     * @param $SQL The SQL query to retrieve
     * @param $value the value used to lookip the record
     * @param $param the type of the $value (i for integer, s for string)
     * @param $list The output format; 
     * 
     *  */

    public static function uniGet($SQL, $value, $param, $list)
    {
        $db = \PNM\Db::getInstance();
        try {
            if ($list) {
                $stmt = $db->prepare($SQL);
            } else { // RETURN_VAL get a single row
                $stmt = $db->prepare($SQL . ' LIMIT 1');
            }
            if (!empty($param)) {
                $stmt->bind_param($param, $value);
            }
            $stmt->execute();
        } catch (\mysqli_sql_exception $e) {
            \PNM\CriticalError::show($e);
        }
        $result = $stmt->get_result();
        if ($result->num_rows !== 0) {
            if ($list == self::RETURN_ASSOC) {
                return $result->fetch_all(MYSQLI_ASSOC);
            } elseif ($list == self::RETURN_INDEXED) {
                return $result->fetch_all(MYSQLI_NUM);
            } else { // RETURN_VAL = return a value from  a single row
                return $result->fetch_row()[0];
            }
        } else {
            // echo "$SQL**$value**param*$param"; //Comment this line
        }
    }

    public static function get($SQL, $value, $param = 's')
    {
        return self::uniGet($SQL, $value, $param, self::RETURN_VAL);
    }

    public static function getList($SQL, $value, $param = 's')
    {
        return self::uniGet($SQL, $value, $param, self::RETURN_ASSOC);
    }
    /*
     * returns a column of values from a certain field as an indexed array
     */

    public static function getColumn($SQL, $value, $param = 's')
    {
        return array_column(self::uniGet($SQL, $value, $param, self::RETURN_INDEXED), 0);
    }

    public static function getThesaurus($thesaurusID)
    {
        return self::getColumn('SELECT item_name FROM thesauri WHERE thesaurus = ? ORDER BY item_name', $thesaurusID, 'i');
    }

    public static function dateStart($dating)
    {
        return self::get('SELECT sort_date_range_start FROM thesauri WHERE item_name = ?', $dating);
    }

    public static function dateEnd($dating)
    {
        return self::get('SELECT sort_date_range_end FROM thesauri WHERE item_name = ?', $dating);
    }

    public static function latitude($place_name)
    {
        return self::get('SELECT latitude FROM places WHERE place_name = ?', $place_name);
    }

    public static function findGroupTitle($id)
    {
        return self::get('SELECT title FROM find_groups WHERE find_groups_id = ?', $id, 'i');
    }

    public static function name_types_idGet($name_type)
    {
        return self::get('SELECT name_types_id FROM name_types WHERE title = ?', $name_type);
    }

    public static function collectionsGet($collection)
    {
        $res = self::get('SELECT collections_id FROM collections WHERE title = ?', $collection);
        if (empty($res)) {
            $res = self::get('SELECT collections_id FROM collections WHERE full_name_en = ?', $collection);
        }
        if (empty($res)) {
            $res = self::get('SELECT collections_id FROM collections WHERE full_name_national_language = ?', $collection);
        }
        return $res;
    }
}
