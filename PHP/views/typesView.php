<?php
/*
 * Description of typesView
 * This class renders the types page
 */

namespace PNM\views;

class typesView extends View
{

    public function echoRender(&$data)
    {
        (new HeadView())->render(HeadView::HEADERSLIM, "Name Types");
        $typesMV = new name_typesMicroView();
        //     print_r($data);
        ?>
        <div class="row">
            <div class="column">
                <h2><?= $data->data[0]['title'] ?></h2>
                <?php
                if (!empty($data->data[0]['children'])) {
                    foreach ($data->data[0]['children'] as $rec) {
                        echo '<h3>', $typesMV->render($rec['title'], $rec['name_types_id']), '</h3>';
                        $this->renderChildren($rec, 0);
                    }
                }
                ?>
            </div>
            <div class="column -wide">
                <h2><?= $data->data[1]['title'] ?></h2>
                <?php
                if (!empty($data->data[0]['children'])) {
                    foreach ($data->data[1]['children'] as $rec) {
                        echo '<h3>', $typesMV->render($rec['title'], $rec['name_types_id']), '</h3>';
                        $this->renderChildren($rec, 0);
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }
}
