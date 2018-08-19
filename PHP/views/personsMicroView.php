<?php

/*
 * Description of personsMicroView
 * This is a MicroView used to render links to persons
 */

namespace PNM\views;

class personsMicroView extends MicroView
{

    protected $controller = "person";

    protected function echoTemplate()
    {
        if (!empty($this->secondinput)) {
            $res = $this->secondinput . ' (' . $this->value . ')';
        } else {
            $res = $this->value;
        }
        return <<<EOF
<span class="person"><a href="$this->url">$res</a></span>
EOF;
    }
}
