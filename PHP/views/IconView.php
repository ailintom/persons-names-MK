<?php
/*
 * Description of IconView
 * This class handles the svg icons
 *
 */

namespace PNM\views;

class IconView
{

    private static $svgSymbols = null;
    /*
     * This function returns the HTML code for a particular svg icon
     * @param $name icon name
     */

    public static function get($name, $screenReaderText = '')
    {
        if (isset(self::$svgSymbols[$name])) {
            return self::$svgSymbols[$name]['html'];
        }
        $svg = file_get_contents(dirname(__DIR__) . "/assets/icons/$name.svg");
        //$innerSVG = null;

        $matches = [];
        preg_match('#>(.*)</svg>#', $svg, $matches);
        $innerSVG = $matches[1];
        preg_match('#viewBox="(.*)">#', $svg, $matches);
        $viewBox = $matches[1];
        $html = "<svg class='icon -$name' viewBox='{$viewBox}'>"
                . "<use xlink:href='#icon-$name'></use></svg>"
                . ($screenReaderText ? "<span class='sr-only'>$screenReaderText</span>" : "");
        self::$svgSymbols[$name] = [
            'symbol' => "<symbol id='icon-$name'>$innerSVG</symbol>",
            'html' => $html
        ];
        return $html;
    }
    /*
     * This function returns the HTML code a section in the page footer containing all svg icons used on a page
     */

    public static function echoSvgFooter()
    {
        if (empty(self::$svgSymbols)) {
            return null;
        }
        ?>
        <svg xmlns="http://www.w3.org/2000/svg" class="hidden">
            <?php
            foreach (self::$svgSymbols as $svgSymbol) {
                echo $svgSymbol['symbol'];
            }
            ?>
        </svg>
        <?php
    }
}
