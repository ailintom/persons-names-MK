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

namespace PNM;

/*
 *
 *
 */

class workshopView extends View
{
    /*
     *
     *   'workshops_id', 'title', 'production_place', 'production_place_note', 'dating', 'dating_note', 'note']);
     */

    public function echoRender(&$data)
    {
        (new Head())->render(Head::HEADERSLIM, $data->get('title'));
        ?>
        <dl>
            <?php
            $placesMV = new placesMicroView();
            echo( $this->descriptionElement('Production place', $placesMV->render($data->get('production_place')), $data->get('production_place_note')));
            echo $this->descriptionElement('Date', $data->get('dating'), $data->get('dating_note'), 'period');
            echo( $this->descriptionElement('Note', $data->get('note'), null, 'note'));
            echo $this->descriptionElement('Bibliography', $data->get('bibliography'));
            //renderURL
            ?>
        </dl>
        <h2>Inscribed objects in this find group</h2>
        <?php
        $total = count($data->data['inscriptions']->data);
        for ($i = 0; $i < $total; $i++) {
            $data->data['inscriptions']->data[$i]['object_type'] = $this->renderObjectType($data->data['inscriptions']->data[$i]['object_type']);
            $data->data['inscriptions']->data[$i]['text_content'] = $this->renderTextContent($data->data['inscriptions']->data[$i]['text_content']);
            if (!empty($data->data['inscriptions']->data[$i]['note'])) {
                $data->data['inscriptions']->data[$i]['status'] .= ' (' . $data->data['inscriptions']->data[$i]['note'] . ')';
            }
        }
        $tableCo = new Table($data->get('inscriptions'), 'inscriptions_id', 'inscription', 'sort', '#results');
        $tableCo->renderTable(['status', 'title', 'material',
            'size', 'text_content', 'dating', 'inst_prov_temp', 'orig_prod_temp', 'owner'], ['Type', 'Object', 'Material', 'Size, mm',
            'Text', 'Date', 'Provenance', 'Origin/Prod. place', 'Owner'], true);
    }

    protected function inscribedObjects($id_coll, $count)
    {
        if (!empty($count)) {
            return '<a href="' . Request::makeURL('inscriptions') . '?collection=' . urlencode($id_coll) . '">' . $count . '</a>';
        }
    }
}
