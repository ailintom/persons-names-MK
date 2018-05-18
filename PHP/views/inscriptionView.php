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

//put your code here

    public function Render($data) {
        return $this->renderMainEntry($data);
    }

    public function renderMainEntry($data) {
$placesMV = new placesMicroView;
        $res = '<h1>' . $data->get('title') . '</h1>';
        $res .= '<p>' . $data->get('inv_no') . '</p>';
        $res .= '<dl>';
        $res .= $this->descriptionElement('Alternative inv.', $data->get('alternative_inv_no'), NULL, 'alternative_inv_no');
        $res .= $this->descriptionElement('Obsolete inv.', $data->get('obsolete_inv_no'), NULL, 'obsolete_inv_no');
        $res .= $this->descriptionElement('Erroneusly', $data->get('erroneous_inv_no'), NULL, 'erroneous_inv_no');

        $res .= $this->descriptionElement('Type', $data->get('object_type'), NULL, 'type');
        $res .= $this->descriptionElement('Subtype', $data->get('object_subtype'), NULL, 'type');
        $res .= $this->descriptionElement('Material', $data->get('material'), NULL, 'type');
        $res .= $this->descriptionElement('Size', $this->Size($data), NULL, 'type');

        $res .= $this->descriptionElement('Text', $data->get('text_content'), NULL, 'type');
        $res .= $this->descriptionElement('Script', $data->get('script'), NULL, 'type');
        $res .= $this->descriptionElement('Date', $data->get('dating'), $data->get('dating_note'), 'period');
        $res .= $this->descriptionElement('Provenance', $placesMV->render( $data->get('provenance')), $data->get('provenance_note'), 'place');
        $res .= $this->descriptionElement('Installation place', $placesMV->render( $data->get('installation_place')), $data->get('installation_place_note'), 'place');
        $res .= $this->descriptionElement('Origin', $placesMV->render( $data->get('origin')), $data->get('origin_note'), 'place');
        $res .= $this->descriptionElement('Production place',$placesMV->render(  $data->get('production_place')), $data->get('production_place_note'), 'place');
        $res .= $this->descriptionElement('Owner', '');
        $res .= $this->descriptionElement('Bibliography', $data->get('bibliography'));

        $res .= '</dl>';

        $res .= '<h2>People</h2><ul class="locations">';
        $objAtt = $data->get('attestations');
        $titlesView = New titlesMicroView();
        $attView = New attestationsMicroView();
        $spellView = New spellingsMicroView();
        $loc = NULL;

        $currentLoc = NULL;
        foreach ($objAtt->data as $Att) {
            if ($loc !== $Att['location']) {
                $res .= $this->writeLoc($loc, $currentLoc);
                $currentLoc = NULL;
                $loc = $Att['location'];
            }
            //'attestations_id', 'location', 'gender', 'title_string', 'personal_name', 'status', 'note'
            $currentLoc .= '<li><h4 id="' . $Att['attestations_id'] . '"><span class="tit">' . $Att['title_string'] . '</span> <span class="pn">' . $Att['personal_name'] . '</span></h4>';

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
                        $currentLoc .= '<a href="' . BASE . 'name/' . $name['personal_names_id'] . '.html#' . $spelling['spellings_id'] . '">';
                        if ($spellingPerNameCount++ == 0) {
                            $currentLoc .= $name['personal_name'] . ' ';
                        }
                        $currentLoc .=  $spellView->render($spelling['spelling'], $spelling['spellings_id'])
                                . '</a>';
                        // . '<img class="spelling" src="' . BASE . 'assets/spellings/' . $spelling['spellings_id'] . '.png" alt="' . $spelling['spelling'] . '"></span>'
                        $currentLoc .= $this->processAltReadings($spelling['alt_readings']);
                        $currentLoc .= '</span>';
                    }
                }
                $currentLoc .= '</td>';
            }

            $currentLoc .= '</tr></table>';
            //spellings
            /*
             * 
             *                 

              <td><span class="name"><a href="name/184549478.html#243269678">sꜣ.t-jp <span class="spelling-attestation"><img class="spelling" src="<?=BASE?>assets/spellings/243269678.png" alt="zA&amp;t i p:F27"></span></a></span></td>
              </tr></table>
             * 
             * attestations_id', 'wording', 'gender', 'predic', 'person
             * 
             *                <ul class="bonds">
              <li>Husband (<span class="wording">implied</span>): <span class="attestation"><a href="inscription/33556283.html#67117487"><span class="tit">jr.j-pꜥ.t; ḥꜣ.tj-ꜥ; jm.j-rꜣ nj.wt; ṯꜣ.tj</span> <span class="pn">sꜣ-mnṯ.w</span></a></span></li>
              <li>Children
              <ul class="children">
              <li>Daughter (<span class="wording">mst.n</span>): <span class="attestation"><a href="inscription/33556283.html#67117491"><span class="tit">snb</span></a></span></li>
              <li>Son (<span class="wording">ms.n</span>): <span class="attestation"><a href="inscription/33556283.html#67117495"><span class="tit">ḫtm.w-bj.tj; ḥm-nṯr n jmn</span> <span class="pn">snb⸗f-n⸗j</span></a></span></li>
              </ul>
              </li>
              </ul>
             * 
             */
            if (!empty($Att['bonds']->data)) {

                $bonds = $Att['bonds']->data;

                $currentLoc .= '<ul class="bonds">';
                $currentcat = 0;
                $bondsincurrentcat = [];
                foreach ($bonds as $bond) {
                    if ($currentcat !== $bond['predic_cat']) {
                        if (!empty($currentcat)) {
                            $currentLoc .= $this->processBondCat($currentcat, $bondsincurrentcat, $attView);
                        }
                        $currentcat = $bond['predic_cat'];
                        $bondsincurrentcat = [$bond];
                    } else {
                        array_push($bondsincurrentcat, $bond);
                    }
                }
                $currentLoc .= $this->processBondCat($currentcat, $bondsincurrentcat, $attView);
                $currentLoc .= '</ul>';
            }

            $currentLoc .= '</li>';
        }
        $res .= $this->writeLoc($loc, $currentLoc);

        $res .= '</ul>';
        return $res;
    }

   

    protected function processBondCat($currentcat, $bondsincurrentcat, $attView) {
        $res = "";
        if (empty($bondsincurrentcat)) {
            return NULL;
        } elseif (count($bondsincurrentcat) == 1) {
            $gen = $this->genderedDesignations($currentcat, $bondsincurrentcat[0]['gender']);
            return '<li>' . ($gen ?: ObjectBonds::BOND_TYPES_SING[$currentcat] . ' ')
                    . '(<span class="wording">' . $bondsincurrentcat[0]['wording'] . '</span>): '
                    . $attView->render($bondsincurrentcat[0]['title_string'], $bondsincurrentcat[0]['attestations_id'], $bondsincurrentcat[0]['personal_name'])
                    . '.</li>';

            // $this->field_names = new FieldList(['attestations_id', 'wording', 'predicate', 'predic', 'title_string', 'personal_name', 'gender', 'predic_cat']);
            //<li>Mother (<span class="wording">ms.n</span>): <span class="attestation"><a href="inscription/33556283.html#67117489"><span class="tit">nb.t pr</span> <span class="pn">sꜣ.t-jp</span></a></span>.</li>
        } elseif (count($bondsincurrentcat) > 1) {
            $res = '<li>' . ObjectBonds::BOND_TYPES_PLUR[$currentcat] . '<ul class="children">';
            foreach ($bondsincurrentcat as $bond) {
                $res .= '<li>';
                $res .= $this->genderedDesignations($currentcat, $bond['gender']);
                $res .= '(<span class="wording">' . $bond['wording'] . '</span>): '
                        . $attView->render($bond['title_string'], $bond['attestations_id'], $bond['personal_name'])
                        . '.</li>';
            }
            $res .= '</ul>';
            return $res;

            /*
             * 
             * <li>Children
              <ul class="children">
              <li>Daughter (<span class="wording">sꜣ.t⸗f</span>): <span class="attestation"><a href="inscription/33556283.html#67117491"><span class="tit">snb</span></a></span>.</li>
              <li>Son (<span class="wording">sꜣ.⸗f</span>): <span class="attestation"><a href="inscription/33556283.html#67117495"><span class="tit">ḫtm.w-bj.tj; ḥm-nṯr n jmn</span> <span class="pn">snb⸗f-n⸗j</span></a></span>.</li>
             * 
             */
        }

        //      $currentLoc .= '<li>' . ObjectBonds::BOND_TYPES_SING[$bond['predic_cat']] . ' ' . $bond['predic'] . ' ' . $attView->render($bond['person'], $bond['attestations_id']) . '</li>';
        //BOND_TYPES_SING
        // ['attestations_id', 'wording', 'predicate', 'predic', 'person', 'gender', 'predic_cat';
    }

    protected function genderedDesignations($currentcat, $gender) {
        if (ObjectBonds::BOND_TYPES_PLUR[$currentcat] == 'Children') {
            switch ($gender) {
                case 'm':
                    return 'Son ';

                case 'f':
                    return 'Daughter ';

                default:
                    return 'Child ';
            }
        } elseif (ObjectBonds::BOND_TYPES_PLUR[$currentcat] == 'Siblings') {
            switch ($gender) {
                case 'm':
                    return 'Brother ';

                case 'f':
                    return 'Sister ';

                default:
                    return 'Sibling ';
            }
        } else {
            return NULL;
        }
    }

    protected function processAltReadings($objAltReadings) {
        if (!empty($objAltReadings->data)) {
            $res = '(alternative reading' . (count($objAltReadings->data > 0) ? 's' : NULL ) . ': ';
            $count = 0;
            $objView = new personal_namesMicroView();
            foreach ($objAltReadings->data as $altReading) {
                $res .= ($count++ > 0 ? ', ' : NULL) . $objView->render($altReading['personal_name'], $altReading['personal_names_id']);
            }
            $res .= ')';
            return $res;
        }
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

}
