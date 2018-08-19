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

$ClassName = "PNM\\controllers\\" . Request::get('controller') . "Controller";
$controllerObj = new $ClassName();
$controllerObj->load();
require 'views/footer.php';

/*
 * Config.php is not included in the published source code for security reasons
 * It should look as follows:
  class Config {
  const DB_CONFIG = ['host' => 'host',
  'port' => '3306',
  'username' => 'user',
  'password' => 'password',
  'db_prefix' => 'db_ver' // version numbers (0, 1, 2, etc.) are added to this prefix
  ];
  const VERSIONS = [[1, "15.04.2018"], [2, "16.04.2018"]];
  const BASE = '/subpath/';
  const HOST = 'https://pnm.uni-mainz.de';
  const IMPRESSUM = "";
  const PRIVACY = "";
  public static function maxVer() {
  return self::VERSIONS[count(self::VERSIONS) - 1][0];
  }
  }
 *
 */
