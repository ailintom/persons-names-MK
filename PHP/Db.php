<?php

/*
 * This static class is used to access the database
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
