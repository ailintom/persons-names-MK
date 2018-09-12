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
    $file;
// if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

try {
    $ClassName = "PNM\\controllers\\" . Request::get('controller') . "Controller";
    $controllerObj = new $ClassName();
    $controllerObj->load();
    require 'views/footer.php';
} catch (\Throwable $e) {
        CriticalError::show($e);
}

/*
 * Config.php is not included in the published source code for security reasons
 * It should look as follows:
  class Config {
  const DB_CONFIG = ['host' => 'host',
  'port' => 3306,
  'username' => 'user',
  'password' => 'password',
  'db_prefix' => 'db_ver' // version numbers (0, 1, 2, etc.) are added to this prefix
  ];
  const VERSIONS = [[1, "15.04.2018"], [2, "16.04.2018"]];
  const ROWS_ON_PAGE = 50;
  const MAX_STABLE_URL_LENGTH = 35;
  const FORMAL_PATTERNS_ID = 251658605;
  const SEMANTIC_CLASSES_ID = 251658604;
  const START_PAGE_TEXT = "<p>The online database “Persons and Names of the Middle Kingdom” (PNM) is developed as part of the project <a href='https://www.aegyptologie.uni-mainz.de/umformung-und-variabilitaet-1/'>“Umformung und Variabilität im Korpus altägyptischer Personennamen 2055–1550 v.&nbsp;Chr.”</a>, funded by the <a href='http://www.fritz-thyssen-stiftung.de'>Fritz Thyssen Foundation</a></p>";
  const BASE = '/subpath/';
  const HOST = 'https://pnm.uni-mainz.de';
  const IMPRESSUM = "";
  const PRIVACY = "";

  }
 *
 */
