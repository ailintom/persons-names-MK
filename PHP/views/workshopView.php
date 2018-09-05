<?php
/*
 * Description of workshopView
 * Class used to render a page representing a single workshop
 */

namespace PNM\views;

class workshopView extends View
{
    /*
     *
     *   'workshops_id', 'title', 'production_place', 'production_place_note', 'dating', 'dating_note', 'note']);
     */

    public function echoRender(&$data)
    {
        (new HeadView())->render(HeadView::HEADERSLIM, $data->get('title'));
        ?>
        <dl>
            <?php
            $placesMV = new placesMicroView();
            echo( $this->descriptionElement('Production place', $placesMV->render($data->get('production_place')), $data->get('production_place_note')));
            echo $this->descriptionElement('Date', $data->get('dating'), $data->get('dating_note'), 'period');
            echo( $this->descriptionElement('Note', $data->get('note'), null, 'note'));
            echo $this->descriptionElement('Bibliography', $this->renderBiblio($data->get('bibliography')));
            //renderURL
            ?>
        </dl>
        <h2>Inscribed objects attributed to this workshop</h2>
        <?php
        $total = count($data->data['inscriptions']->data);
        for ($i = 0; $i < $total; $i++) {
            $data->data['inscriptions']->data[$i]['object_type'] = $this->renderObjectType($data->data['inscriptions']->data[$i]['object_type']);
            $data->data['inscriptions']->data[$i]['text_content'] = $this->renderTextContent($data->data['inscriptions']->data[$i]['text_content']);
            if (!empty($data->data['inscriptions']->data[$i]['note'])) {
                $data->data['inscriptions']->data[$i]['status'] .= ' (' . $data->data['inscriptions']->data[$i]['note'] . ')';
            }
        }
        $tableCo = new TableView($data->get('inscriptions'), 'inscriptions_id', 'inscription', 'sort', '#results');
        $tableCo->renderTable(['status', 'title', 'material',
            'size', 'text_content', 'dating', 'inst_prov_temp', 'orig_prod_temp', 'owner'], ['Type', 'Object', 'Material', 'Size, mm',
            'Text', 'Date', 'Provenance', 'Origin/Prod.', 'Owner'], true, '',['Type', 'Object', 'Material', 'Size in mm',
            'Text type', 'Date', 'Provenance or installation place', 'the origin of the owner or the place of production', 'Owner’s name']);
    }

    protected function inscribedObjects($id_coll, $count)
    {
        if (!empty($count)) {
            return '<a href="' . \PNM\Request::makeURL('inscriptions') . '?collection=' . urlencode($id_coll) . '">' . $count . '</a>';
        }
    }
}
