<?php

/*
 * Description of criteriaMicroView
 * This is a MicroView used to render links to criteria
 */

namespace PNM\views;

class criteriaMicroView extends MicroView
{

    protected $controller = "criterion";

    protected function echoTemplate()
    {
        return <<<EOT
<a href="$this->url">$this->value</a>
EOT;
    }
}
