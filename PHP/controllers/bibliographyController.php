<?php

/*
 * Description of bibliographyController
 *
 * This is a controller loading the bibliography. It does not support any user input
 */

namespace PNM\controllers;

class bibliographyController
{

    public function load()
    {
        $bibliography = new \PNM\models\bibliography();
        $view = new \PNM\views\bibliographyView();
        $view->echoRender($bibliography);
    }
}
