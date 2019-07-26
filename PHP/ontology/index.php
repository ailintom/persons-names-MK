<?php

namespace PNM\ontology;


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
    $base_dir = dirname(__DIR__, 1).'/';
    set_include_path(dirname(__DIR__, 1));

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
    $ClassName = "PNM\\views\\HeadView";
    $controllerObj = new $ClassName();
     
    $controllerObj->render(\PNM\views\HeadView::HEADERSLIM, 'Ontology');

    readfile(__DIR__."/ontology.html");

    require 'views/footer.php';
} catch (\Throwable $e) {
        \PNM\CriticalError::show($e);
}



