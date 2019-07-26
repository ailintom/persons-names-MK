<?php

/*
 * Description of MicroView
 * This is a parent class for classes used to render a link to a particular entry in the database and to correcly represent its title
 *
 */

namespace PNM\views;

use \PNM\Request;

class MicroView
{

    protected $template = null;
    protected $secondinput = null;
    protected $url = null;
    protected $value = null;
    protected $controller = null;

    public function render($inputvalue, $inputid = null, $secondinput = null)
    {
        if (empty($inputvalue)){
            return null;
        }
        $this->value = htmlspecialchars(strip_tags($inputvalue), ENT_HTML5);
        if (isset($secondinput)) {
            $this->secondinput = htmlspecialchars(strip_tags($secondinput), ENT_HTML5);
        }
        if (isset($inputid)) {
            $this->url = $this->makeURL($inputid);
        }
        $res = $this->echoTemplate();
        return $res;
    }

    protected function makeURL($inputid)
    {
        return Request::makeURL($this->controller, $inputid);
    }

    public function echoRender($inputvalue, $inputid = null, $secondinput = null)
    {
        echo ($this->render($inputvalue, $inputid, $secondinput) );
    }

    protected function echoTemplate()
    {
        return <<<EOF
<a href="$this->url">$this->value</a>
EOF;
    }
}
