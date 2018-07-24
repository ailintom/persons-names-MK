<?php
/*
 * MIT License
 *
 * Copyright (c) 2017 Alexander Ilin-Tomich (unless specified otherwise for individual source files and documents)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace PNM\views;

/**
 * Description of bibliographyView
 *
 * @author Tomich
 */
class typesView extends View
{

    public function echoRender(&$data)
    {
        (new HeadView())->render(HeadView::HEADERSLIM, "Name types");
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
