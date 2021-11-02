<?php

/*
 * Description of inscriptionView
 * Class used to render a page representing a single inscription with all attestations of personal names on it
 */

namespace PNM\views;

use \PNM\Request;

class objView extends View {

    public function echoRender(&$data) {
        (new HeadView())->render(HeadView::HEADERSLIM, $data->get('title'));
        $placesMV = new placesMicroView();
        echo $this->descriptionElement('Inv. no', $this->renderInvNos($data->get('inv_no')), null, 'alternative_inv_no');
        echo $this->descriptionElement('Alternative inv.', $this->renderInvNos($data->get('alternative_inv_no')), null, 'alternative_inv_no');
        echo $this->descriptionElement('Obsolete inv.', $this->renderInvNos($data->get('obsolete_inv_no')), null, 'obsolete_inv_no');
        echo $this->descriptionElement('Erroneous inv.', $this->renderInvNos($data->get('erroneous_inv_no')), null, 'erroneous_inv_no');
        echo $this->descriptionElement('PM', $data->get('topbib_id'), null, 'biblio-ref-no-author-date');

        echo $this->descriptionElement('Type', $this->renderObjectType($data->get('object_type')), null, 'type');
        echo $this->descriptionElement('Subtype', $data->get('object_subtype'), null, 'type');
        echo $this->descriptionElement('Material', $data->get('material'), null, 'type');
        echo $this->descriptionElement('Size', $this->size($data->get('length'), $data->get('height'), $data->get('width'), $data->get('thickness')), null, 'type');

        echo $this->descriptionElement('Provenance', $placesMV->render($data->get('provenance')), $data->get('provenance_note'), 'place');
        if (!empty($data->get('find_groups_id'))) {
            echo $this->descriptionElement('Find group', \PNM\Note::processID($data->get('find_groups_id')), null, 'find_group');
        }
        echo $this->descriptionElement('Intalled at', $placesMV->render($data->get('installation_place')), $data->get('installation_place_note'), 'place');

        echo $this->descriptionElement('Produced at', $placesMV->render($data->get('production_place')), $data->get('production_place_note'), 'place');
        if (count($data->get('workshops')->data) > 0) {
            echo $this->descriptionElement('Workshop', $this->renderWorkshop($data->get('workshops')), null, 'workshop');
        }


        echo $this->descriptionElement('Note', $data->get('note'), null, 'note');
        echo $this->descriptionElement('Bibliography', $this->renderBiblio($data->get('bibliography')));
        echo $this->descriptionElement('Inscriptions', $this->renderInscriptions($data->get('inscriptions'), null, 'type'));

        echo '</dl>';
    }

}
