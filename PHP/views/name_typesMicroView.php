<?php

/*
 * Description of name_typesMicroView
 * This is a MicroView used to render links to name types
 */

namespace PNM\views;

class name_typesMicroView extends MicroView
{

    protected $controller = "type";

    protected function echoTemplate()
    {
        return <<<EOF
<a href="$this->url">$this->value</a>
EOF;
    }
}
