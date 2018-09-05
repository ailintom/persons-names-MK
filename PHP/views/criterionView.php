<?php
/*
 * Description of criterionView
 * Class used to render a page representing a single criterion
 */

namespace PNM\views;

class criterionView extends View
{

    public function echoRender(&$data)
    {
        (new HeadView())->render(HeadView::HEADERSLIM, $data->get('title'));
        ?>
        <p class="info-box">
            <?php
            echo( Icon::get('info') . $data->get('criterion'));
            ?>
        </p>
        <dl>
            <?php
            $placesMV = new placesMicroView();
            echo( $this->descriptionElement('Production place', $placesMV->render($data->get('production_place')), null, 'place'));
            echo( $this->descriptionElement('Date', $data->get('dating'), null, 'date'));
            echo( $this->descriptionElement('Bibliography', $this->renderBiblio($data->get('bibliography'))));
            ?>
        </dl>
        <?php
    }
}
