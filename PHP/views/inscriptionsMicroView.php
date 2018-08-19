<?php

/*
 * Description of inscriptionsMicroView
 * This is a MicroView used to render links to inscriptions
 */

namespace PNM\views;

class inscriptionsMicroView extends MicroView
{

    protected $controller = "inscription";

    protected function echoTemplate()
    {
        return <<<EOF
<a href="$this->url">$this->value</a>
EOF;
    }
}
