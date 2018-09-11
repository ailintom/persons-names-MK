<?php
/*
 * Description of collectionView
 * Class used to render a page representing a single collection
 */

namespace PNM\views;

use \PNM\Request;

class collectionView extends View
{

    public function echoRender(&$data)
    {
        (new HeadView())->render(HeadView::HEADERSLIM, $data->get('title'));
        ?>
        <dl>
            <?php
            echo( $this->descriptionElement('Full name', $data->get('full_name_en')));
            echo( $this->descriptionElement('Name in local language', $data->get('full_name_national_language')));
            echo( $this->descriptionElement('Location', $data->get('location')));
            echo( $this->descriptionElement('Inscribed objects in the database', $this->inscribedObjects($data->get('title'), $data->get('inscriptions_count'))));
            echo( $this->descriptionElement('Website', $this->renderURL($data->get('url'))));
            echo( $this->descriptionElement('Online catalogue', $this->renderURL($data->get('online_collection'))));
            $ref = $this->addReference('Trismegistos collection ID', $data->get('tm_coll_id'), \PNM\ExternalLinks::TRISMEGISTOS_COLLECTION);
            $ref = $this->addReference('THOT ID', $data->get('thot_concept_id'), \PNM\ExternalLinks::THOTH_CONCEPT, $ref);
            $ref = $this->addReference('Artefacts of Excavations', $data->get('artefacts_url'), null, $ref);
            echo( $this->descriptionElement('References', $ref));
            ?>
        </dl>
        <h2>Inventory numbers in this collection</h2>
        <?php
        $total = count($data->data['inv_nos']->data);
        for ($i = 0; $i < $total; $i++) {
            $data->data['inv_nos']->data[$i]['object_type'] = $this->renderObjectType($data->data['inv_nos']->data[$i]['object_type']);
            $data->data['inv_nos']->data[$i]['text_content'] = $this->renderTextContent($data->data['inv_nos']->data[$i]['text_content']);
        }
        $tableCo = new TableView($data->get('inv_nos'), 'inscriptions_id', 'inscription', 'sort', '#results');
        $tableCo->renderTable(['inv_no', 'object_type', 'title', 'material',
            'size', 'text_content', 'dating', 'inst_prov_temp', 'orig_prod_temp', 'owner'], ['Inv. no.', 'Type', 'Object', 'Material', 'Size, mm',
            'Text', 'Date', 'Provenance', 'Origin/Prod.', 'Owner'], true, 'inv. no.', ['Inventory no.', 'Type', 'Object', 'Material', 'Size in mm',
            'Text type', 'Date', 'Provenance or installation place', 'the origin of the owner or the place of production', 'Owner’s name']);
    }

    protected function inscribedObjects($id_coll, $count)
    {
        if (!empty($count)) {
            return '<a href="' . Request::makeURL('inscriptions') . '?collection=' . urlencode($id_coll) . '">' . $count . '</a>';
        }
    }
}
