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

class Db
{

    private static $instance = null;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_INDEX);
            $dbName = Config::DB_CONFIG['db_prefix'] . Request::get('used_ver');
            try {
                $mysqli = new \mysqli(Config::DB_CONFIG['host'], Config::DB_CONFIG['username'], Config::DB_CONFIG['password'], $dbName, Config::DB_CONFIG['port']);
                $mysqli->set_charset('utf8');
            } catch (\mysqli_sql_exception $e) {
                CriticalError::show($e);
            }
            self::$instance = $mysqli;
        }
        return self::$instance;
    }

    public static function close()
    {
        $thread = self::$instance->thread_id;
        self::$instance->kill($thread);
        self::$instance->close();
        unset(self::$instance);
    }
}
