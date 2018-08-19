<?php

/*
 * Description of titlesMicroView
 * This is a MicroView used to render links to titles
 */

namespace PNM\views;

class titlesMicroView extends MicroView
{

    protected $controller = "title";

    protected function echoTemplate()
    {
        return <<<EOT
<span class="title"><a href="$this->url">$this->value</a></span>
EOT;
    }
}
