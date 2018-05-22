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

class collectionView extends View {
    /*
     * 
     *   $this->field_names = new FieldList(['collections_id', 'title', 'full_name_en', 'full_name_national_language', 'location', 'IFNULL(url, online_collection)', 'IF(online_collection>"", "available", "")', 'tm_coll_id',
      'SELECT COUNT(DISTINCT inscriptions_id) FROM inv_nos WHERE inv_nos.collections_id = collections.collections_id and status<>"erroneous"'],
      ['collections_id', 'title', 'full_name_en', 'full_name_national_language',  'location', 'url', 'online_collection', 'tm_coll_id',
      'inscriptions_count']);
     */

    public function echoRender(&$data) {
        (New Head)->render(Head::HEADERSLIM, $data->get('title'));
        ?>
        <dl>
            <?php
            echo( $this->descriptionElement('Full name', $data->get('full_name_en')));
            echo( $this->descriptionElement('Name in local language', $data->get('full_name_national_language')));
            echo( $this->descriptionElement('Location', $data->get('location')));
            echo( $this->descriptionElement('Inscribed objects in the database', $this->inscribedObjects($data->get('title'), $data->get('inscriptions_count'))));
            echo( $this->descriptionElement('Website', $this->renderURL($data->get('url'))));
            echo( $this->descriptionElement('Online catalogue', $this->renderURL($data->get('online_collection'))));
            echo( $this->descriptionElement('Trismegistos collection ID', $this->renderURL($data->get('tm_coll_id'), 'https://www.trismegistos.org/collection/')));
            //renderURL
            ?>
        </dl>
        <h2>Objects in this collection</h2>

        <?php
        $total = count($data->data['inv_nos']->data);
        for ($i = 0; $i < $total; $i++) {
            $data->data['inv_nos']->data[$i]['object_type'] = $this->renderObjectType($data->data['inv_nos']->data[$i]['object_type']);
            $data->data['inv_nos']->data[$i]['text_content'] = $this->renderTextContent($data->data['inv_nos']->data[$i]['text_content']);
        }
        $tableCo = new Table($data->get('inv_nos'), 'inscriptions_id', 'inscription', 'sort', '#results');
        $tableCo->render_table(['inv_no', 'object_type', 'title', 'material',
            'size', 'text_content', 'dating', 'inst_prov_temp', 'orig_prod_temp', 'owner'], ['Inv. no.', 'Type', 'Object', 'Material', 'Size, mm',
            'Text', 'Date', 'Provenance', 'Origin/Prod. place', 'Owner'], TRUE);
    }

    protected function inscribedObjects($id_coll, $count) {
        if (!empty($count)) {
            return '<a href="' . Request::makeURL('inscriptions') . '?collection=' . urlencode($id_coll) . '">' . $count . '</a>';
        }
    }

}
