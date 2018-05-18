<?php

$svgSymbols = [];

function icon($name, $screenReaderText = '') {
    global $svgSymbols;

    if (isset($svgSymbols[$name])) {
        return $svgSymbols[$name]['html'];
    }
    /*
      $svg = simplexml_load_file(__DIR__ . "/../assets/icons/$name.svg");

     */
    $svg = file_get_contents(__DIR__ . "/assets/icons/$name.svg");
    //$innerSVG = NULL;
    /*
      foreach ($svg->children() as $child) {
      $innerSVG .= $child->asXML();
      }
     */
    $matches = [];
    preg_match('#>(.*)</svg>#', $svg, $matches);
    $innerSVG = $matches[1];
    preg_match('#viewBox="(.*)">#', $svg, $matches);
    $viewBox = $matches[1];
    $html = "<svg class='icon -$name' viewBox='{$viewBox}'>"
            . "<use xlink:href='#icon-$name'></use></svg>"
            . ($screenReaderText ? "<span class='sr-only'>$screenReaderText</span>" : "");

    $svgSymbols[$name] = [
        'symbol' => "<symbol id='icon-$name'>$innerSVG</symbol>",
        'html' => $html
    ];

    return $html;
}


