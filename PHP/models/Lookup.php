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
class Lookup {

     
    static function get($SQL, $value, $param = 's') {
        $db = Db::getInstance();

        try {
            $stmt = $db->prepare($SQL . ' LIMIT 1');
            $stmt->bind_param($param, $value);
            $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            CriticalError::Show($e);
        }
        $result = $stmt->get_result();
        if ($result->num_rows !== 0) {
            return $result->fetch_row()[0];
        }else{
            echo "$SQL**$value**param*$param"; //Comment this line
        }
    }
    static function dateStart($dating){
       
        return self::get('SELECT sort_date_range_start FROM thesauri WHERE item_name = ?', $dating);
    }
       static function dateEnd($dating){
                 return self::get('SELECT sort_date_range_end FROM thesauri WHERE item_name = ?', $dating);
    }
      static function name_types_idGet($name_type){
                 return self::get('SELECT name_types_id FROM name_types WHERE title = ?', $name_type);
    }


}
