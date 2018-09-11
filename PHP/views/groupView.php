<?php
/*
 * Description of groupView
 * Class used to render a page representing a single find group
 */

namespace PNM\views;

use \PNM\Request;

class groupView extends View
{

    public function echoRender(&$data)
    {
        (new HeadView())->render(HeadView::HEADERSLIM, $data->get('title'));
        ?>
        <dl>
            <?php
            $placesMV = new placesMicroView();
            echo( $this->descriptionElement('Site', $placesMV->render($data->get('site'))));
            echo( $this->descriptionElement('Area', $data->get('site_area')));
            echo( $this->descriptionElement('Location', $data->get('exact_location')));
            echo( $this->descriptionElement('Type', $data->get('find_group_type')));
            echo( $this->descriptionElement('Architecture', $data->get('architecture')));
            echo( $this->descriptionElement('Human remains', $data->get('human_remains')));
            echo( $this->descriptionElement('Non-inscribed finds', $data->get('finds')));
            echo( $this->descriptionElement('Disturbance', $data->get('disturbance')));
            echo $this->descriptionElement('Date', $data->get('dating'), $data->get('dating_note'), 'period');
            echo( $this->descriptionElement('Note', $data->get('note'), null, 'note'));
            echo $this->descriptionElement('Bibliography', $this->renderBiblio($data->get('bibliography')));
            //renderURL
            ?>
        </dl>
        <h2>Inscribed objects in this find group</h2>
        <?php
        $total = count($data->data['inscriptions']->data);
        for ($i = 0; $i < $total; $i++) {

            $data->data['inscriptions']->data[$i]['object_type'] = $this->renderObjectType($data->data['inscriptions']->data[$i]['object_type']);
            $data->data['inscriptions']->data[$i]['text_content'] = $this->renderTextContent($data->data['inscriptions']->data[$i]['text_content']);
        }
        $tableCo = new TableView($data->get('inscriptions'), 'inscriptions_id', 'inscription', 'sort', '#results');
        $tableCo->renderTable(['object_type', 'title', 'material',
            'size', 'text_content', 'dating', 'inst_prov_temp', 'orig_prod_temp', 'owner'], ['Type', 'Object', 'Material', 'Size, mm',
            'Text', 'Date', 'Provenance', 'Origin/Prod.', 'Owner'], true, '', ['Type', 'Object', 'Material', 'Size in mm',
            'Text type', 'Date', 'Provenance or installation place', 'the origin of the owner or the place of production', 'Owner’s name']);
    }

    protected function inscribedObjects($id_coll, $count)
    {
        if (!empty($count)) {
            return '<a href="' . Request::makeURL('inscriptions') . '?collection=' . urlencode($id_coll) . '">' . $count . '</a>';
        }
    }
}
