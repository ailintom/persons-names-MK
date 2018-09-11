<?php

/*
 * Description of spellingsMicroView
 * This is a MicroView used to render links to spellings
 */

namespace PNM\views;

use PNM\Request;

class spellingsMicroView extends MicroView
{

    protected function echoTemplate()
    {
        if ($this->value == 'generic') {
            return <<<EOF
<span class="spelling-attestation"><span class="spelling">$this->value</span></span>
EOF;
        } else {
            return <<<EOF
<span class="spelling-attestation"><img class="spelling" src="$this->url" alt="$this->value"></span>
EOF;
        }
    }

    protected function makeURL($inputid)
    {
        return Request::makeURL('assets/spellings', $inputid, null, null, true, -1, true) . '.png';
    }
}
