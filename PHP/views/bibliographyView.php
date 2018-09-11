<?php

/*
 * Description of bibliographyView
 * Class used to render bibliography
 */

namespace PNM\views;

use \PNM\Request;

class bibliographyView extends View
{

    public function echoRender(&$data)
    {
        (new HeadView())->render(HeadView::HEADERSLIM, 'Bibliography');
        foreach ($data->data as $row) {
            echo '<a href="' . Request::makeURL('publication', $row[$data->getFieldName(1)]) . '">' . $row[$data->getFieldName(0)] . "</a><br>";
        }
    }
}
