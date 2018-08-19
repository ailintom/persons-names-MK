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
        //  $filter = new \PNM\models\Filter ([new \PNM\models\Rule('author_year', 'startswith', 'c', 's'), new \PNM\models\Rule('author_year', 'inexact', '20')]);
        $bibliography = new \PNM\models\bibliography(); //$sort = null, $start = 0, $count = 0, Filter $filter = null
        $view = new \PNM\views\bibliographyView();
        $view->echoRender($bibliography);
    }
}
