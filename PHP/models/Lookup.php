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
 * Description of Date
 *
 * @author Tomich
 */
class Lookup
{

    const TEXT_CONTENT_THESAURUS = 4;
    const SCRIPT_THESAURUS = 12;
    const OBJECT_TYPES_THESAURUS = 1;
    const RETURN_VAL = 0;
    const RETURN_ASSOC = 1;
    const RETURN_INDEXED = 2;

    public static function get($SQL, $value, $param = 's')
    {
        return self::uniGet($SQL, $value, $param, self::RETURN_VAL);
    }

    public static function getList($SQL, $value, $param = 's')
    {
        return self::uniGet($SQL, $value, $param, self::RETURN_ASSOC);
    }

    public static function getColumn($SQL, $value, $param = 's')
    {
        return array_column(self::uniGet($SQL, $value, $param, self::RETURN_INDEXED), 0);
    }

    public static function uniGet($SQL, $value, $param, $list)
    {
        $db = Db::getInstance();
        try {
            if ($list) {
                $stmt = $db->prepare($SQL);
            } else {
                $stmt = $db->prepare($SQL . ' LIMIT 1');
            }
            if (!empty($param)) {
                $stmt->bind_param($param, $value);
            }
            $stmt->execute();
        } catch (\mysqli_sql_exception $e) {
            CriticalError::show($e);
        }
        $result = $stmt->get_result();
        if ($result->num_rows !== 0) {
            if ($list == self::RETURN_ASSOC) {
                return $result->fetch_all(MYSQLI_ASSOC);
            } elseif ($list == self::RETURN_INDEXED) {
                return $result->fetch_all(MYSQLI_NUM);
            } else {
                return $result->fetch_row()[0];
            }
        } else {
            // echo "$SQL**$value**param*$param"; //Comment this line
        }
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
