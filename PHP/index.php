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
 * Config.php is not included in the source code for security reasons
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
