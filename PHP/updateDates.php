<?php

/*
 * This script is a front controller 
 */

namespace PNM;

error_reporting(E_ALL);
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
spl_autoload_register(function ($class) {
    /*
     * based on https://www.php-fig.org/psr/psr-4/examples/
     * 
     */

// project-specific namespace prefix
    $prefix = 'PNM\\';

// base directory for the namespace prefix
    $base_dir = __DIR__;


// does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

// get the relative class name
    $relative_class = substr($class, $len);

// replace the namespace prefix with the base directory, replace namespace
// separators with directory separators in the relative class name, append
// with .php
    $file = $base_dir . '/' . str_replace('\\', '/', $relative_class) . '.php';

// if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});
$db = array_map(function(array $vers) {
    mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_INDEX);

    $usedVer = $vers[0];

    $dbName = Config::DB_CONFIG['db_prefix'] . $usedVer;
    try {
        $mysqli = new \mysqli(Config::DB_CONFIG['host'], Config::DB_CONFIG['username'], Config::DB_CONFIG['password'], $dbName, Config::DB_CONFIG['port']);
        $mysqli->set_charset('utf8');
        return $mysqli;
    } catch (\mysqli_sql_exception $e) {
        CriticalError::show($e);
    }
}, Config::VERSIONS);
//$db = \PNM\Db::getInstance(1);
$listdbtables = array_column($db[0]->query('SHOW TABLES')->fetch_all(), 0);
$versionNumbers = array_column(Config::VERSIONS, 0);

foreach ($listdbtables as $table) {
    $fields = array_column($db[0]->query('SHOW COLUMNS FROM ' . $table)->fetch_all(), 0);
    $id_field = $fields[0];


    if (substr($table, -5) == '_temp' || !in_array("date_created", $fields) || !in_array("date_changed", $fields)) { // ignore temp tables
        continue;
    }

    $comparedFields = array_diff($fields, [$id_field, "date_created", "date_changed"]);
    $comparedFieldsSQL = implode(" AND ", array_map(function($fieldname) {
                return " t1.`" . $fieldname . "` <=> " . " t0.`" . $fieldname . "`";
            }, $comparedFields));


    $db[0]->query("UPDATE " . $table . " SET date_created = '" . Request::verDate($versionNumbers[0]) . "' , date_changed = '" . Request::verDate($versionNumbers[0]) . "';");
    for ($i = 1; $i < count($versionNumbers); $i++) {

        $sql = "SELECT * FROM " . $table;
        $verIndex = $i; //array_search($i, $versionNumbers);
        $prevVerIndex = $verIndex - 1;
        $sqlUpdateNew = "UPDATE `" . Config::DB_CONFIG['db_prefix'] . $versionNumbers[$verIndex] . "`.`" . $table .
                "` SET date_created = '" . Request::verDate($versionNumbers[$verIndex]) . "' , date_changed = '" . Request::verDate($versionNumbers[$verIndex]) . "' " .
                " WHERE NOT EXISTS (SELECT * FROM " . Config::DB_CONFIG['db_prefix'] . $versionNumbers[$prevVerIndex] . "." . $table .
                " WHERE " . Config::DB_CONFIG['db_prefix'] . $versionNumbers[$verIndex] . "." . $table . ".`" . $id_field . "` = " .
                Config::DB_CONFIG['db_prefix'] . $versionNumbers[$prevVerIndex] . "." . $table . ".`" . $id_field . "`) ;";
    

        $sqlUpdateUnChanged = "UPDATE `" . Config::DB_CONFIG['db_prefix'] . $versionNumbers[$verIndex] . "`.`" . $table . "` AS t1 INNER JOIN `" . Config::DB_CONFIG['db_prefix'] . $versionNumbers[$prevVerIndex] . "`.`" . $table . "` AS t0 ON t1.`" . $id_field . "` = t0.`" . $id_field . "` SET t1.date_created = t0.date_created , t1.date_changed = t0.date_changed WHERE " . $comparedFieldsSQL . ";";

        $sqlUpdateChanged = "UPDATE `" . Config::DB_CONFIG['db_prefix'] . $versionNumbers[$verIndex] . "`.`" . $table . "` AS t1 INNER JOIN `" . Config::DB_CONFIG['db_prefix'] . $versionNumbers[$prevVerIndex] . "`.`" . $table . "` AS t0 ON t1.`" . $id_field . "` = t0.`" . $id_field . "` SET t1.date_created = t0.date_created, t1.date_changed = '" . Request::verDate($versionNumbers[$verIndex]) . "'  WHERE NOT (" . $comparedFieldsSQL . ");";
        // echo ("\n" .$sqlUpdateNew . "\n" . $sqlUpdateUnChanged . "\n" . $sqlUpdateChanged . "\n" );
        $db[$verIndex]->query($sqlUpdateUnChanged);
        $db[$verIndex]->query($sqlUpdateNew);
        $db[$verIndex]->query($sqlUpdateChanged);

  
    }
    echo ("\n ***  " . $table . " done");
   
}


