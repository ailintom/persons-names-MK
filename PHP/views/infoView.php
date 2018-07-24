<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PNM\views;

/**
 * Description of infoView
 *
 * @author Tomich
 */
class infoView
{

    public function echoRender($data)
    {
        (new HeadView())->render(HeadView::HEADERSLIM, $data[0]);
        $dnoteobj = new \PNM\Note($data[1]);
        echo $dnoteobj->ParsedNote;
    }
}
