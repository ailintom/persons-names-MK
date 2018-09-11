<?php

/*
 * Description of inscriptionView
 * Class used to render a page representing a single inscription with all attestations of personal names on it
 */

namespace PNM\views;
use \PNM\Request;

class inscriptionView extends View
{

    public function echoRender(&$data)
    {
        (new HeadView())->render(HeadView::HEADERSLIM, $data->get('title'));
        $placesMV = new placesMicroView();
        echo '<p>' . $this->renderInvNos($data->get('inv_no'), true) . '</p>';
        echo '<dl>';
        echo $this->descriptionElement('Alternative inv.', $this->renderInvNos($data->get('alternative_inv_no')), null, 'alternative_inv_no');
        echo $this->descriptionElement('Obsolete inv.', $this->renderInvNos($data->get('obsolete_inv_no')), null, 'obsolete_inv_no');
        echo $this->descriptionElement('Erroneous inv.', $this->renderInvNos($data->get('erroneous_inv_no')), null, 'erroneous_inv_no');
        echo $this->descriptionElement('Type', $this->renderObjectType($data->get('object_type')), null, 'type');
        echo $this->descriptionElement('Subtype', $data->get('object_subtype'), null, 'type');
        echo $this->descriptionElement('Material', $data->get('material'), null, 'type');
        echo $this->descriptionElement('Size', $this->size($data), null, 'type');
        echo $this->descriptionElement('Text', $this->renderTextContent($data->get('text_content')), null, 'type');
        echo $this->descriptionElement('Script', $data->get('script'), null, 'type');
        echo $this->descriptionElement('Date', $data->get('dating'), $data->get('dating_note'), 'period');
        echo $this->descriptionElement('Provenance', $placesMV->render($data->get('provenance')), $data->get('provenance_note'), 'place');
        if (!empty($data->get('find_groups_id'))) {
            echo $this->descriptionElement('Find group', \PNM\Note::processID($data->get('find_groups_id')), null, 'find_group');
        }
        echo $this->descriptionElement('Intalled at', $placesMV->render($data->get('installation_place')), $data->get('installation_place_note'), 'place');
        echo $this->descriptionElement('Origin', $placesMV->render($data->get('origin')), $data->get('origin_note'), 'place');
        echo $this->descriptionElement('Produced at', $placesMV->render($data->get('production_place')), $data->get('production_place_note'), 'place');
        if (count($data->get('workshops')->data) > 0) {
            echo $this->descriptionElement('Workshop', $this->renderWorkshop($data->get('workshops')), null, 'workshop');
        }
//
        echo $this->descriptionElement('Owner', '');
        echo $this->descriptionElement('Note', $data->get('note'), null, 'note');
        echo $this->descriptionElement('Bibliography', $this->renderBiblio($data->get('bibliography')));
        echo '</dl>';
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
            $status = (empty($Att['status']) ? null : ' (' . $Att['status'] . ')');
            $tit = empty($Att['title_string']) ? null : '<span class="tit">' . $Att['title_string'] . '</span> ';
            $currentLoc .= '<li><h4 id="' . \PNM\ID::shorten($Att['attestations_id']) . '"><i>' . $tit . '<span class="pn">' . $Att['personal_name'] . '</span></i>' . $doss . $status . '</h4>';
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
            $currentLoc .= '<td><span class="gender" title="' . self::genderTitle($Att['gender']) . '">' . $Att['gender'] . '</span></td>';
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

    protected function writeLoc($loc, $currentLoc)
    {
        if (!empty($currentLoc)) {
            return '<li><h3>' . ($loc ?: '&nbsp;') . '</h3><ul class="attestations">' . $currentLoc . ' </ul></li>';
        }
    }
    /*
     * renders the size data
     */

    private function size(&$data)
    {
        // 'length', 'height', 'width', 'thickness',
        $length = $data->get('length');
        if (empty($length)) {
            $length = $data->get('height');
        }
        $width = $data->get('width');
        if (empty($length) & !empty($width)) {
            $length = $width;
            $width = null;
        }
        $thickness = $data->get('thickness');
        $thicknessProcessed = (empty($thickness) ? null : '×' . $thickness);
        if (!empty($length)) {
            return $length . (empty($width) ? null : '×' . $width . $thicknessProcessed) . ' mm';
        }
    }
    /*
     * Renders workshops associated with a particular inscription
     */

    private function renderWorkshop($data)
    {
        return implode(", ", array_map(array($this, 'renderSingleWorkshop'), $data->data));
    }
    /*
     * Renders a single workshop; used in the previous function
     */

    private function renderSingleWorkshop($Wk)
    {
        $wkMV = new workshopsMicroView();
        return $wkMV->render($Wk['title'], $Wk['workshops_id']) . ' (' . $Wk['status'] . (empty($Wk['note']) ? null : ', ' . $Wk['note']) . ')';
    }
    /*
     * Renders inventory numbers of a certain type
     */

    protected function renderInvNos($data, $isMain = false)
    {
        if (empty($data->data)) {
            return null;
        }
        $sortedData = $data->data;
        usort($sortedData, function ($a, $b) {
            return strnatcasecmp($a['title'], $b['title']) == 0 ? strnatcasecmp($a['inv_no'], $b['inv_no']) : strnatcasecmp($a['title'], $b['title']);
        });
        $title = null;
        $id = null;
        $invs = null;
        $res = null;
        $concat = ($isMain ? '+' : ', '); //if several main inv_nos are present, they refer to separate parts of the object listed as a+b+c; otherwise inv_nos are treated as alternative numbers of the same object
        $colView = new collectionsMicroView();
        $invView = new inv_nosMicroView();
        foreach ($sortedData as $row) {
            if ($title !== $row['title']) {
                $res .= $this->renderSingleInvNo($colView, $res, $concat, $invs, $title, $id);
                $title = $row['title'];
                $id = $row['collections_id'];
                $invs = $invView->render($row['inv_no']);
            } else {
                $invs .= (empty($invs) ? null : $concat) . $invView->render($row['inv_no']);
            }
        }
        $res .= $this->renderSingleInvNo($colView, $res, $concat, $invs, $title, $id);
        return $res;
    }
    /*
     * Renders a single inv_no; is used in the previous function
     */

    protected function renderSingleInvNo(&$colView, $res, $concat, $invs, $title, $id)
    {
        if (!empty($invs)) {
            return (empty($res) ? null : $concat) . $colView->render($title, $id) . ' ' . $invs;
        }
    }
}
