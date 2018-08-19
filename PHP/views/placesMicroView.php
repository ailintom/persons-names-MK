<?php

/*
 * Description of placesMicroView
 * This is a MicroView used to render links to places
 */

namespace PNM\views;

class placesMicroView extends MicroView
{

    protected $controller = "place";

    protected function echoTemplate()
    {
        if (!empty($this->value)) {
            $model = new \PNM\models\placeMicroModel();
            $model->find($this->value);
            $this->url = $this->makeURL($model->get('places_id'));
            if (!empty($model->get('long_place_name'))) {
                $this->secondinput = ' title="' . $model->get('long_place_name') . '"';
            }
            return <<<EOT
<a href="$this->url"$this->secondinput>$this->value</a>
EOT;
        }
    }
}
