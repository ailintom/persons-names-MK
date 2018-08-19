<?php

/*
 * Description of personal_namesMicroView
 * This is a MicroView used to render links to personal names
 */

namespace PNM\views;

class personal_namesMicroView extends MicroView
{

    protected $controller = "name";

    protected function echoTemplate()
    {
        return <<<EOT
<span class="name"><a href="$this->url">$this->value</a></span>
EOT;
    }
}
