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
 *       $this->field_names = new FieldList(['title', 'object_type', 'object_subtype', 'material', 'length', 'height', 'width', 'thickness', 'find_groups_id', 'text_content',
  'script', 'provenance', 'provenance_note', 'installation_place', 'installation_place_note', 'origin', 'origin_note', 'production_place', 'production_place_note',
  'dating', 'dating_note', 'last_king_id', 'note']);
 */

//inscriptionView
class inscriptionView extends View {

    public function echoRender(&$data) {
        (New Head)->render(Head::HEADERSLIM, $data->get('title'));
        $placesMV = new placesMicroView;
        $find_groupMV = new find_groupsMicroView;
        echo '<p>' . $data->get('inv_no') . '</p>';
        echo '<dl>';
        echo $this->descriptionElement('Alternative inv.', $data->get('alternative_inv_no'), NULL, 'alternative_inv_no');
        echo $this->descriptionElement('Obsolete inv.', $data->get('obsolete_inv_no'), NULL, 'obsolete_inv_no');
        echo $this->descriptionElement('Erroneusly', $data->get('erroneous_inv_no'), NULL, 'erroneous_inv_no');

        echo $this->descriptionElement('Type', $this->renderObjectType($data->get('object_type')), NULL, 'type');
        echo $this->descriptionElement('Subtype', $data->get('object_subtype'), NULL, 'type');
        echo $this->descriptionElement('Material', $data->get('material'), NULL, 'type');
        echo $this->descriptionElement('Size', $this->Size($data), NULL, 'type');

        echo $this->descriptionElement('Text', $this->renderTextContent($data->get('text_content')), NULL, 'type');
        echo $this->descriptionElement('Script', $data->get('script'), NULL, 'type');
        echo $this->descriptionElement('Date', $data->get('dating'), $data->get('dating_note'), 'period');
        echo $this->descriptionElement('Provenance', $placesMV->render($data->get('provenance')), $data->get('provenance_note'), 'place');
        if (!empty($data->get('find_groups_id'))) {
            echo $this->descriptionElement('Find group', $find_groupMV->render(Lookup::findGroupTitle($data->get('find_groups_id')), $data->get('find_groups_id')), NULL, 'find_group');
        }
        echo $this->descriptionElement('Intalled at', $placesMV->render($data->get('installation_place')), $data->get('installation_place_note'), 'place');
        echo $this->descriptionElement('Origin', $placesMV->render($data->get('origin')), $data->get('origin_note'), 'place');
        echo $this->descriptionElement('Produced at', $placesMV->render($data->get('production_place')), $data->get('production_place_note'), 'place');
        if (count($data->get('workshops')->data) > 0) {
            echo $this->descriptionElement('Workshop', $this->renderWorkshop($data->get('workshops')), NULL, 'workshop');
        }
//
        echo $this->descriptionElement('Owner', '');
        echo $this->descriptionElement('Bibliography', $data->get('bibliography'));

        echo '</dl>';

        echo '<h2>People</h2><ul class="locations">';
        $objAtt = $data->get('attestations');
        $titlesView = New titlesMicroView();
        $attView = New attestationsMicroView();
        $spellView = New spellingsMicroView();
        $loc = NULL;

        $currentLoc = NULL;
        foreach ($objAtt->data as $Att) {
            if ($loc !== $Att['location']) {
                echo $this->writeLoc($loc, $currentLoc);
                $currentLoc = NULL;
                $loc = $Att['location'];
            }
            if (count($Att['persons']->data) > 0) {
                $doss = ', dossier' . (count($Att['persons']->data) > 1 ? 's' : NULL) . ': ' . $this->renderPersons($Att['persons']) ;
            } else {
                $doss = NULL;
            }
            $currentLoc .= '<li><h4 id="' . ID::shorten($Att['attestations_id']) . '"><span class="tit">' . $Att['title_string'] . '</span> <span class="pn">' . $Att['personal_name'] . '</span>' . $doss . '</h4>';

            $spellings = $Att['spellings']->getSpellings();
            $titles = $Att['titles']->data;

            $currentLoc .= '<table class="name-box"><tr><th></th>';
            if (!empty($titles)) {
                $currentLoc .= '<th>Title</th>'; //if the attestation has associated titles, display the title heading
            }
            if (!empty($spellings) & !empty($spellings[0]['spellings'])) {
                $currentLoc .= '<th>Name</th>'; //if the attestation has associated titles, display the name heading
            }
            $currentLoc .= '</tr><tr>';
            $currentLoc .= '<td><span class="gender" title="' . self::GenderTitle($Att['gender']) . '">' . $Att['gender'] . '</span></td>';

            if (!empty($titles)) {
                $currentLoc .= '<td>';
                $titleCount = 0;
                foreach ($titles as $title) {
                    if ($titleCount++ > 0) {
                        $currentLoc .= '; ';
                    }
                    $currentLoc .= $titlesView->render($title['title'], $title['titles_id']);
                }
                $currentLoc .= '</td>';
            }



            if (!empty($spellings) & !empty($spellings[0]['spellings'])) {
                $currentLoc .= '<td>';
                $spellingCount = 0;
                foreach ($spellings as $name) {
                    if ($spellingCount++ > 0) {
                        $currentLoc .= ' / ';
                    }
                    $spellingPerNameCount = 0;
                    foreach ($name['spellings'] as $spelling) {
                        if ($spellingPerNameCount > 0) {
                            $currentLoc .= ', ';
                        }
                        $currentLoc .= '<span class="name">';
                        $currentLoc .= '<a href="' . Request::makeURL('name', [$name['personal_names_id'], $spelling['spellings_id']]) . '">';
                        if ($spellingPerNameCount++ == 0) {
                            $currentLoc .= $name['personal_name'] . ' ';
                        }
                        $currentLoc .= $spellView->render($spelling['spelling'], $spelling['spellings_id'])
                                . '</a>';
                        // . '<img class="spelling" src="' . Config::BASE . 'assets/spellings/' . $spelling['spellings_id'] . '.png" alt="' . $spelling['spelling'] . '"></span>'
                        $currentLoc .= $this->processAltReadings($spelling['alt_readings']);
                        $currentLoc .= '</span>';
                    }
                }
                $currentLoc .= '</td>';
            }

            $currentLoc .= '</tr></table>';
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
            return '<li><h3>' . $loc . '</h3><ul class="attestations">' . $currentLoc . ' </ul></li>';
        }
    }

    private function Size($data) {
        // 'length', 'height', 'width', 'thickness',
        $length = $data->get('length');
        if (empty($length)) {
            $length = $data->get('height');
        }
        $width = $data->get('width');
        if (empty($length) & !empty($width)) {
            $length = $width;
            $width = NULL;
        }
        $thickness = $data->get('thickness');
        $thicknessProcessed = (empty($thickness) ? NULL : '×' . $thickness);
        if (!empty($length)) {
            return $length . (empty($width) ? NULL : '×' . $width . $thicknessProcessed) . ' mm';
        }
    }

    private function renderWorkshop($data) {
        return implode(", ", array_map(array($this, 'renderSingleWorkshop'), $data->data));
    }

    private function renderSingleWorkshop($Wk) {
        $wkMV = New workshopsMicroView;
        return $wkMV->render($Wk['title'], $Wk['workshops_id']) . ' (' . $Wk['status'] . (empty($Wk['note']) ? NULL : ', ' . $Wk['note']) . ')';
    }

}
