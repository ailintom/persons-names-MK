<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PNM;

/**
 * Description of infoView
 *
 * @author Tomich
 */
class infoView {

    public function echoRender($data) {
        (New Head)->render(Head::HEADERSLIM, $data[0]);


        echo $data[1];
    }

}
