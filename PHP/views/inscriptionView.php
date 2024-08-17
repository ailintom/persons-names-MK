<?php

/*
 * Description of inscriptionView
 * Class used to render a page representing a single inscription with all attestations of personal names on it
 */

namespace PNM\views;

use \PNM\Request;

class inscriptionView extends View {


    public function echoRender(&$data) {
        (new HeadView())->render(HeadView::HEADERSLIM, $data->get('title'));
        $placesMV = new placesMicroView();

        /* echo $this->descriptionElement('Type', $this->renderObjectType($data->get('object_type')), null, 'type');
          echo $this->descriptionElement('Subtype', $data->get('object_subtype'), null, 'type');
          echo $this->descriptionElement('Material', $data->get('material'), null, 'type');
          echo $this->descriptionElement('Size', $this->size($data), null, 'type'); */
        echo $this->descriptionElement('Text', $this->renderTextContent($data->get('text_content')), null, 'type');
        echo $this->descriptionElement('Script', $data->get('script'), null, 'type');
        echo $this->descriptionElement('Date', $data->get('dating'), $data->get('dating_note'), 'period');
        /* echo $this->descriptionElement('Provenance', $placesMV->render($data->get('provenance')), $data->get('provenance_note'), 'place');
          if (!empty($data->get('find_groups_id'))) {
          echo $this->descriptionElement('Find group', \PNM\Note::processID($data->get('find_groups_id')), null, 'find_group');
          }
          echo $this->descriptionElement('Intalled at', $placesMV->render($data->get('installation_place')), $data->get('installation_place_note'), 'place'); */
        echo $this->descriptionElement('Origin', $placesMV->render($data->get('origin')), $data->get('origin_note'), 'place');
        /*     echo $this->descriptionElement('Produced at', $placesMV->render($data->get('production_place')), $data->get('production_place_note'), 'place');
          if (count($data->get('workshops')->data) > 0) {
          echo $this->descriptionElement('Workshop', $this->renderWorkshop($data->get('workshops')), null, 'workshop');
          }

         */
//

        echo $this->descriptionElement('Owner', '');
        echo $this->descriptionElement('Note', $data->get('note'), null, 'note');
        $ref = $this->addReference('Trismegistos Texts ID', $data->get('tmtexts_id'), \PNM\ExternalLinks::TRISMEGISTOS_TEXTS);
        $ref = $this->addReference('TLA Texts ID', $data->get('tla'), \PNM\ExternalLinks::TLA_TEXTS, $ref);
        echo( $this->descriptionElement('External links', $ref));
        echo $this->descriptionElement('Bibliography', $this->renderBiblio($data->get('bibliography')));
        echo '</dl>';
        $objObjects = $data->get('objects');
        if (count($objObjects->data) > 1) {
            echo '<h2>Objects</h2><ul class="attestations">';
            foreach ($objObjects->data as $objObject) {
                echo '<li><h4 id="object' . \PNM\ID::shorten($objObject['objects_id']) . '">' . $objObject['title'] . '</h4>';
                $this->renderObjects($objObject);
            }
            echo '</ul>';
        } else {
            echo '<h2 id="object' . \PNM\ID::shorten($objObjects->data[0]['objects_id']) . '">Object</h2>';
            //print_r($objObjects);
            $this->renderObjects($objObjects->data[0]);
        }

        echo '<h2>People</h2><ul class="locations">';
        $objAtt = $data->get('attestations');
        $titlesView = new titlesMicroView();
        $attView = new attestationsMicroView();
        $spellView = new spellingsMicroView();
        $loc = null;
        $currentLoc = null;
        foreach ($objAtt->data as $Att) {
            if ($loc !== $Att['location']) {
                echo $this->writeLoc($loc, $currentLoc);
                $currentLoc = null;
                $loc = $Att['location'];
            }
            if (count($Att['persons']->data) > 0) {
                $doss = ', dossier' . (count($Att['persons']->data) > 1 ? 's' : null) . ': ' . $this->renderPersons($Att['persons']);
            } else {
                $doss = null;
            }
            $status = (($Att['status']=='') ? '' : ' (' . $Att['status'] . ')');
            $tit = empty($Att['title_string']) ? null : '<span class="tit">' . $Att['title_string'] . '</span> ';
            $currentLoc .= '<li><h4 id="' . \PNM\ID::shorten($Att['attestations_id']) . '"><i>' . $tit . '<span class="pn">' . $Att['personal_name'] . '</span></i>' . $doss . $status . '</h4>';
            $spellings = $Att['spellings']->getSpellings();
            $titles = $Att['titles']->data;

            $attestationRender = [];
            //           $representation = $Att['representation'];
            $currentLoc .= '<table class="name-box"><tr><th></th>';
            //            
//Render table data
            $renderGender = '<span class="gender" title="' . self::genderTitle($Att['gender']) . '">' . $Att['gender'] . '</span>';
            $this->pushAttetastionElement($attestationRender, $renderGender, self::genderHead);

            if (!empty($titles)) {
                $renderTitles = '';

                $titleCount = 0;
                foreach ($titles as $title) {
                    if ($titleCount++ > 0) {
                        $renderTitles .= '; ';
                    }
                    $renderTitles .= $titlesView->render($title['title'], $title['titles_id']);
                }
                $this->pushAttetastionElement($attestationRender, $renderTitles, self::titleHead);
            }
            if (!empty($spellings) & !empty($spellings[0]['spellings'])) {

                $nameRender = '';
                $spellingCount = 0;
                foreach ($spellings as $name) {
                    if ($spellingCount++ > 0) {
                        $nameRender .= ' / ';
                    }
                    $spellingPerNameCount = 0;
                    foreach ($name['spellings'] as $spelling) {
                        if ($spellingPerNameCount > 0) {
                            $nameRender .= ', ';
                        }
                        $nameRender .= '<span class="name">';
                        $nameRender .= '<a href="' . Request::makeURL('name', [$name['personal_names_id'], $spelling['spellings_id']]) . '">';
                        if ($spellingPerNameCount++ == 0) {
                            $nameRender .= $name['personal_name'] . ' ';
                        }
                        $nameRender .= $spellView->render($spelling['spelling'], $spelling['spellings_id'])
                                . '</a>';
                        $nameRender .= $this->processAltReadings($spelling['alt_readings']);

                        $nameRender .= '</span>';
                        $curEpithet_mdc = $spelling['epithet_mdc'];
                        $curClassifier = $spelling['classifier'];
                        if (!empty($curEpithet_mdc) || !empty($curClassifier)) {
                            if (!empty($nameRender)) {
                                $this->pushAttetastionElement($attestationRender, $nameRender, self::nameHead);
                            }
                            $nameRender = '';
                            $spellingCount = 0;
                            $spellingPerNameCount = 0;
                        }
                        if (!empty($curEpithet_mdc)) {
                            $this->pushAttetastionElement($attestationRender, $this->render_mdc($curEpithet_mdc), self::epithetHead);
                        }

                        if (!empty($curClassifier)) {

                            $this->pushAttetastionElement($attestationRender, $this->render_mdc($curClassifier), self::classifierHead);
                        }
                    }
                }
                if (!empty($nameRender)) {
                    $this->pushAttetastionElement($attestationRender, $nameRender, self::nameHead);
                }
            }

            $currentLoc .= $this->attestationTable($attestationRender);
            //spellings
            if (!empty($Att['bonds']->data)) {
                $currentLoc .= $this->renderBonds($Att['bonds']->data, $attView);
            }
            $currentLoc .= '</li>';
        }
        echo $this->writeLoc($loc, $currentLoc);
        echo '</ul>';
        }



    protected function writeLoc($loc, $currentLoc) {
        if (!empty($currentLoc)) {
            return '<li><h3>' . ($loc ?: '&nbsp;') . '</h3><ul class="attestations">' . $currentLoc . ' </ul></li>';
        }
    }

    /*
     * Renders objects associated with a particular inscription
     */

    private function renderObjects($data) {
        $placesMV = new placesMicroView();
        echo '<p>' . $this->renderInvNos($data['inv_no'], true) . '</p>';
        echo '<dl>';
        echo $this->descriptionElement('Alternative inv.', $this->renderInvNos($data['alternative_inv_no']), null, 'alternative_inv_no');
        echo $this->descriptionElement('Obsolete inv.', $this->renderInvNos($data['obsolete_inv_no']), null, 'obsolete_inv_no');
        echo $this->descriptionElement('Erroneous inv.', $this->renderInvNos($data['erroneous_inv_no']), null, 'erroneous_inv_no');
        echo $this->descriptionElement('PM', $data['topbib_id'], null, 'biblio-ref-no-author-date');

        echo $this->descriptionElement('Type', $this->renderObjectType($data['object_type']), null, 'type');
        echo $this->descriptionElement('Subtype', $data['object_subtype'], null, 'type');
        echo $this->descriptionElement('Material', $data['material'], null, 'type');
        echo $this->descriptionElement('Size', $this->size($data['length'], $data['height'], $data['width'], $data['thickness']), null, 'type');
        echo $this->descriptionElement('Provenance', $placesMV->render($data['provenance']), $data['provenance_note'], 'place');
        if (!empty($data['find_groups_id'])) {
            echo $this->descriptionElement('Find group', \PNM\Note::processID($data['find_groups_id']), null, 'find_group');
        }
        echo $this->descriptionElement('Intalled at', $placesMV->render($data['installation_place']), $data['installation_place_note'], 'place');
        echo $this->descriptionElement('Produced at', $placesMV->render($data['production_place']), $data['production_place_note'], 'place');
        if (array_key_exists('workshops', $data)) {
            echo $this->descriptionElement('Workshop', $this->renderWorkshop($data['workshops']), null, 'type');
        }
        //inscriptions
        if (array_key_exists('inscriptions', $data)) {
            echo $this->descriptionElement('Other inscriptions', $this->renderInscriptions($data['inscriptions']), null, 'type');
        }
        echo $this->descriptionElement('Bibliography', $this->renderBiblio($data['bibliography']));
    }

}
