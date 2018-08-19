<?php

/*
 * Description of attestationsMicroView
 * This is a MicroView used to render links to attestations
 */

namespace PNM\views;

class attestationsMicroView extends MicroView
{

    protected $inscriptionID = null;
    protected $controller = 'inscription';

    public function setInscription($inscriptionID)
    {
        $this->inscriptionID = intval($inscriptionID);
    }

    protected function echoTemplate()
    {
        //<span class="tit">nb.t pr</span> <span class="pn">sꜣ.t-jp</span>
        $tit = (empty($this->value) ? null : '<span class="tit">' . $this->value . '</span> ');
        $pn = '<span class="pn">' . $this->secondinput . '</span>';
        return <<<EOT
<a href="$this->url">$tit$pn</a>
EOT;
    }

    protected function makeUrl($inputid)
    {
        if (empty($this->inscriptionID)) {
            return "#" . \PNM\ID::shorten($inputid);
        } else {
            return \PNM\Request::makeURL($this->controller, [$this->inscriptionID, $inputid]);
        }
    }
}
