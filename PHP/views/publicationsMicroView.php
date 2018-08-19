<?php

/*
 * Description of publicationsMicroView
 * This is a MicroView used to render links to publications
 */

namespace PNM\views;

class publicationsMicroView extends MicroView
{

    protected $controller = "publication";

    protected function echoTemplate()
    {
        return <<<EOF
<a href="$this->url">$this->value</a>
EOF;
    }
}
