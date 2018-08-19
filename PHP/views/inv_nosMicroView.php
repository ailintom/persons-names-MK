<?php

/*
 * Description of inv_nosMicroView
 * This is a MicroView used to render inventory numbers
 */

namespace PNM\views;

class inv_nosMicroView extends MicroView
{

    protected function echoTemplate()
    {
        return <<<EOF
<span class="inv_no">$this->value</span>
EOF;
    }
}
