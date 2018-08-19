<?php

/*
 * Description of collectionsMicroView
 * This is a MicroView used to render links to collections
 */

namespace PNM\views;

class collectionsMicroView extends MicroView
{

    protected $controller = "collection";

    protected function echoTemplate()
    {
        return <<<EOF
<span class="collection"><a href="$this->url">$this->value</a></span>
EOF;
    }
}
