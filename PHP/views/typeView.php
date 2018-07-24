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

/*
 *
 *
 */

class typeView extends View
{
    /*
     */

    public function echoRender(&$data)
    {
        (new HeadView())->render(HeadView::HEADERSLIM, $data->get('title'));
        ?>
        <p><span class="note"><?= ($data->get('category') == 'formal' ? 'Formal name pattern' : 'Semantic name class') ?></span></p>
        <dl>
            <?php
            echo( $this->descriptionElement('Superordinate types', $this->superordinate($data->get('parents'))));
            echo( $this->descriptionElement('Subordinate types', null));
            if (!empty($data->get('subtypes')->data[0]['children'])) {
                echo('<dt>Subordinate type:</dt><dd>');
                $this->renderChildren($data->get('subtypes')->data[0], 0);
                echo('</dd>');
            }
            echo( $this->descriptionElement('Total number of attestations of names belonging to this type and its sub-types', '<a href="' . \PNM\Request::makeURL('people') . '?A' . ($data->get('category') == 'formal' ? 'form' : 'sem' ) . '_type' . '=' . urlencode($data->get('title')) . '">' . $data->get('attestations_count') . '</a>'));
            echo $this->descriptionElement('Bibliography', $data->get('bibliography'), null, 'biblio-ref');
            //renderURL
            ?>
        </dl>
        <?php
        if ($data->get('names')->total_count > 0) {
            $tableCo = new TableView($data->get('names'), 'personal_names_id', 'name', 'sort');
            $tableCo->renderTable(['personal_name', 'gender', 'count_attestations', 'usage_period', 'usage_area', 'ranke', 'translation_en'], ['Personal name', 'Gender', 'Atts.', 'Period', 'Area', 'Ranke no.', 'Translation'], true);
        }
    }

    protected function superordinate($data)
    {
        if (empty($data)) {
            return null;
        }
        $res = null;
        $typesMV = new name_typesMicroView();
        for ($i = count($data) - 1; $i >= 0; $i--) {
            $res .= '<ul><li>' . $typesMV->render($data[$i][1], $data[$i][0]);
        }
        $res .= str_repeat('</li></ul>', count($data));
        return $res;
    }
}
