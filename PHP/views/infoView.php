<?php

/*
 * Description of infoView
 * Class used to render a page representing a single information page
 */

namespace PNM\views;

class infoView
{

    public function echoRender($data)
    {
        (new HeadView())->render(HeadView::HEADERSLIM, $data[0]);
        $dnoteobj = new \PNM\Note($data[1]);
        echo $dnoteobj->ParsedNote;
    }
}
